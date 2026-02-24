<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingPage;
use App\Models\AnalyticTracker;
use App\Models\Product;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\PaymentMethod;
use App\Models\SystemSetting;
use App\Mail\OrderCompleted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Traits\HandlesMidtrans;

class PublicPageController extends Controller
{
    use HandlesMidtrans;

    // ── Service fee helper ────────────────────────────────────────────
    private function calcServiceFee(float $amount): array
    {
        $settings    = SystemSetting::pluck('value', 'key');
        $feeType     = $settings['service_fee_type']   ?? 'fixed';
        $feeValue    = (float)($settings['service_fee_amount'] ?? 0);
        $serviceFee  = 0;

        if ($feeValue > 0) {
            $serviceFee = $feeType === 'fixed' ? $feeValue : $amount * ($feeValue / 100);
        }

        return ['fee' => $serviceFee, 'grand' => $amount + $serviceFee];
    }

    // ── Public pages ─────────────────────────────────────────────────
    public function show(Request $request, $slug)
    {
        $landingPage = LandingPage::with(['appearance', 'products' => function($q) {
            $q->with('addOns');
        }])->where('slug', $slug)->firstOrFail();

        AnalyticTracker::create([
            'user_id'        => $landingPage->user_id,
            'landing_page_id'=> $landingPage->id,
            'type'           => 'visit',
            'ip_address'     => $request->ip(),
        ]);

        return view('public.show', compact('landingPage'));
    }

    public function productDetail(Request $request, $slug, $id)
    {
        $landingPage = LandingPage::with(['appearance', 'user'])->where('slug', $slug)->firstOrFail();
        $product     = Product::with('addOns')->where('landing_page_id', $landingPage->id)->findOrFail($id);

        AnalyticTracker::create([
            'user_id'        => $landingPage->user_id,
            'landing_page_id'=> $landingPage->id,
            'type'           => 'visit',
            'ip_address'     => $request->ip(),
        ]);

        return view('public.product_detail', compact('landingPage', 'product'));
    }

// ── Checkout (GET) ───────────────────────────
    public function checkout(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        $productId   = $request->query('product_id');
        $qty         = max(1, (int)$request->query('qty', 1));

        $product = $productId
            ? Product::where('landing_page_id', $landingPage->id)->findOrFail($productId)
            : $landingPage->products()->firstOrFail();

        // Voucher
        $appliedVoucherCode = session('applied_voucher');
        $voucher            = null;
        $discountAmount     = 0;
        $basePrice          = $product->sale_price && $product->sale_price > 0
                                ? $product->sale_price : $product->price;

        if ($appliedVoucherCode) {
            $voucher = Voucher::where('user_id', $landingPage->user_id)
                              ->where('code', $appliedVoucherCode)->first();
            if ($voucher) {
                $discountAmount = $voucher->discount_type === 'fixed'
                    ? $voucher->discount_amount
                    : $basePrice * ($voucher->discount_amount / 100);
            }
        }

        $price       = $basePrice * $qty;
        $totalAmount = max(0, $price - $discountAmount);
        ['fee' => $serviceFee, 'grand' => $grandTotal] = $this->calcServiceFee($totalAmount);

        // Get Client Key for frontend
        $settings   = SystemSetting::pluck('value', 'key');
        $clientKey  = $settings['midtrans_client_key'] ?? config('services.midtrans.client_key');
        $env        = $settings['midtrans_environment'] ?? (config('services.midtrans.is_production') ? 'production' : 'sandbox');
        $isProduction = ($env === 'production');
        
        $snapJsUrl  = $isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';

        // Temporary ref for the form
        $orderRef = 'ORD-' . strtoupper(uniqid());

        return view('public.checkout', compact(
            'landingPage', 'product', 'voucher', 'discountAmount',
            'totalAmount', 'grandTotal', 'serviceFee', 'price', 'qty',
            'clientKey', 'snapJsUrl', 'orderRef'
        ));
    }

    public function applyVoucher(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        $code        = $request->input('code');
        $voucher     = Voucher::where('user_id', $landingPage->user_id)
                              ->where('code', strtoupper($code))->first();

        if ($voucher) {
            session(['applied_voucher' => $voucher->code]);
            return back()->with('success', 'Voucher applied successfully!');
        }

        return back()->with('error', 'Invalid voucher code.');
    }

    // ── Checkout (POST) — generate Snap token dynamically ──
    public function processCheckout(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        $product     = Product::where('landing_page_id', $landingPage->id)
                               ->findOrFail($request->product_id);

        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'order_ref'      => 'required|string',
            'qty'            => 'required|integer|min:1',
        ]);

        // Recalculate
        $appliedVoucherCode = session('applied_voucher');
        $basePrice  = $product->sale_price && $product->sale_price > 0
                        ? $product->sale_price : $product->price;
        $discountAmount = 0;

        if ($appliedVoucherCode) {
            $voucher = Voucher::where('user_id', $landingPage->user_id)
                              ->where('code', $appliedVoucherCode)->first();
            if ($voucher) {
                $discountAmount = $voucher->discount_type === 'fixed'
                    ? $voucher->discount_amount
                    : $basePrice * ($voucher->discount_amount / 100);
            }
        }

        $qty         = max(1, (int)$request->qty);
        $price       = $basePrice * $qty;
        $totalAmount = max(0, $price - $discountAmount);
        ['fee' => $serviceFee, 'grand' => $grandTotal] = $this->calcServiceFee($totalAmount);

        try {
            $this->configureMidtrans();
            
            // Generate Snap token with REAL data
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id'     => $request->order_ref,
                    'gross_amount' => (int) $grandTotal,
                ],
                'item_details' => array_values(array_filter([
                    [
                        'id'       => $product->id,
                        'price'    => (int)($price),
                        'quantity' => 1, // Treat aggregate as 1 item for simpler snap view
                        'name'     => $product->name . ' x' . $qty,
                    ],
                    $discountAmount > 0 ? [
                        'id'       => 'DISCOUNT',
                        'price'    => -(int)$discountAmount,
                        'quantity' => 1,
                        'name'     => 'Diskon Voucher',
                    ] : null,
                    $serviceFee > 0 ? [
                        'id'       => 'SERVICE_FEE',
                        'price'    => (int)$serviceFee,
                        'quantity' => 1,
                        'name'     => 'Biaya Layanan',
                    ] : null,
                ])),
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'email'      => $request->customer_email,
                    'last_name'  => '', // Snap sometimes requires this or handles better if empty
                ],
            ]);

            // Create pending order
            $order = Order::create([
                'user_id'          => $landingPage->user_id,
                'landing_page_id'  => $landingPage->id,
                'product_id'       => $product->id,
                'quantity'         => $qty,
                'customer_name'    => $request->customer_name,
                'customer_email'   => $request->customer_email,
                'total_amount'     => $grandTotal,
                'status'           => 'pending',
                'midtrans_order_id'=> $request->order_ref,
            ]);

            session()->forget('applied_voucher');

            return response()->json([
                'snap_token' => $snapToken,
                'order_id'   => $order->id,
                'status'     => 'ok'
            ]);

        } catch (\Exception $e) {
            Log::error('Process Checkout Error: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal membuat transaksi: ' . $e->getMessage()], 500);
        }
    }

    // ── Midtrans Webhook ─────────────────────────────────────────────
    public function midtransNotification(Request $request)
    {
        $this->configureMidtrans();

        try {
            // Signature Verification
            $serverKey = SystemSetting::where('key', 'midtrans_server_key')->value('value');
            $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed !== $request->signature_key) {
                Log::error('Midtrans signature verification failed!', [
                    'order_id' => $request->order_id,
                    'received' => $request->signature_key
                ]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $notif             = new Notification();
            $transactionStatus = $notif->transaction_status;
            $fraudStatus       = $notif->fraud_status;
            $orderId           = $notif->order_id;

            $order = Order::where('midtrans_order_id', $orderId)->first();
            if (!$order) {
                Log::error('Order not found for Midtrans Notification', ['order_id' => $orderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $this->syncOrderWithMidtrans($order);

            return response()->json(['message' => 'Notification processed successfully']);
        } catch (\Exception $e) {
            Log::error('Midtrans notification exception: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    // ── Success page ─────────────────────────────────────────────────
    public function success(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        $order = null;

        // Automatic sync if order_id is present (from redirect)
        $orderId = $request->query('order_id');
        if ($orderId) {
            $order = Order::where('midtrans_order_id', $orderId)->first();
            if ($order) {
                $this->syncOrderWithMidtrans($order);
                // Refresh order after sync
                $order->refresh();
            }
        }

        // If no successful order found, redirect back to landing page (prevent direct access)
        if (!$order || !in_array($order->status, ['verified', 'completed'])) {
            return redirect()->route('public.show', $slug);
        }

        return view('public.success', compact('slug', 'landingPage', 'order'));
    }

    // ── Review submit ─────────────────────────────────────────────────
    public function submitReview(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_role' => 'nullable|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'review_text'   => 'required|string|max:1000',
        ]);

        $landingPage->reviews()->create([
            'customer_name' => $request->customer_name,
            'customer_role' => $request->customer_role,
            'rating'        => $request->rating,
            'review_text'   => $request->review_text,
            'is_approved'   => true,
        ]);

        return redirect()->route('public.show', $slug)->with('review_success', 'Terima kasih atas review Anda!');
    }
}
