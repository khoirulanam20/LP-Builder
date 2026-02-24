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

class PublicPageController extends Controller
{
    public function show(Request $request, $slug)
    {
        $landingPage = LandingPage::with(['appearance', 'products' => function($q) {
            $q->with('addOns');
        }])->where('slug', $slug)->firstOrFail();

        // Track Visit
        AnalyticTracker::create([
            'user_id' => $landingPage->user_id,
            'landing_page_id' => $landingPage->id,
            'type' => 'visit',
            'ip_address' => $request->ip()
        ]);

        return view('public.show', compact('landingPage'));
    }

    public function productDetail(Request $request, $slug, $id)
    {
        $landingPage = LandingPage::with(['appearance', 'user'])->where('slug', $slug)->firstOrFail();
        $product = Product::with('addOns')->where('landing_page_id', $landingPage->id)->findOrFail($id);

        // Track View (optional)
        AnalyticTracker::create([
            'user_id' => $landingPage->user_id,
            'landing_page_id' => $landingPage->id,
            'type' => 'visit',
            'ip_address' => $request->ip()
        ]);

        return view('public.product_detail', compact('landingPage', 'product'));
    }

    public function checkout(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        
        $productId = $request->query('product_id');
        $qty = max(1, (int)$request->query('qty', 1));
        if ($productId) {
            $product = Product::where('landing_page_id', $landingPage->id)->findOrFail($productId);
        } else {
            $product = $landingPage->products()->firstOrFail();
        }

        $paymentMethods = PaymentMethod::where('user_id', $landingPage->user_id)->get();
        
        // Check for applied voucher in session
        $appliedVoucherCode = session('applied_voucher');
        $voucher = null;
        $discountAmount = 0;
        
        $price = $product->sale_price && $product->sale_price > 0 ? $product->sale_price : $product->price;

        if ($appliedVoucherCode) {
            $voucher = Voucher::where('user_id', $landingPage->user_id)->where('code', $appliedVoucherCode)->first();
            if ($voucher) {
                if ($voucher->discount_type == 'fixed') {
                    $discountAmount = $voucher->discount_amount;
                } else {
                    $discountAmount = $price * ($voucher->discount_amount / 100);
                }
            }
        }

        $price = $price * $qty;
        $totalAmount = max(0, $price - $discountAmount);

        // Service Fee Calculation
        $serviceFeeType = SystemSetting::where('key', 'service_fee_type')->value('value') ?? 'fixed';
        $serviceFeeValue = SystemSetting::where('key', 'service_fee_amount')->value('value') ?? 0;
        $serviceFee = 0;

        if ($serviceFeeValue > 0) {
            if ($serviceFeeType == 'fixed') {
                $serviceFee = $serviceFeeValue;
            } else {
                $serviceFee = $totalAmount * ($serviceFeeValue / 100);
            }
        }

        $grandTotal = $totalAmount + $serviceFee;

        return view('public.checkout', compact('landingPage', 'product', 'paymentMethods', 'voucher', 'discountAmount', 'totalAmount', 'grandTotal', 'serviceFee', 'price', 'qty'));
    }

    public function applyVoucher(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        $code = $request->input('code');

        $voucher = Voucher::where('user_id', $landingPage->user_id)->where('code', strtoupper($code))->first();

        if ($voucher) {
            session(['applied_voucher' => $voucher->code]);
            return back()->with('success', 'Voucher applied successfully!');
        }

        return back()->with('error', 'Invalid voucher code.');
    }

    public function processCheckout(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        $product = Product::where('landing_page_id', $landingPage->id)->findOrFail($request->product_id);

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'payment_proof' => 'required|image|max:2048',
        ]);

        // Recalculate Total
        $appliedVoucherCode = session('applied_voucher');
        $price = $product->sale_price && $product->sale_price > 0 ? $product->sale_price : $product->price;
        $discountAmount = 0;
        
        if ($appliedVoucherCode) {
            $voucher = Voucher::where('user_id', $landingPage->user_id)->where('code', $appliedVoucherCode)->first();
            if ($voucher) {
                if ($voucher->discount_type == 'fixed') {
                    $discountAmount = $voucher->discount_amount;
                } else {
                    $discountAmount = $price * ($voucher->discount_amount / 100);
                }
            }
        }

        $qty = max(1, (int)$request->input('qty', 1));
        $price = $price * $qty;
        $totalAmount = max(0, $price - $discountAmount);

        // Service Fee Calculation
        $serviceFeeType = SystemSetting::where('key', 'service_fee_type')->value('value') ?? 'fixed';
        $serviceFeeValue = SystemSetting::where('key', 'service_fee_amount')->value('value') ?? 0;
        $serviceFee = 0;

        if ($serviceFeeValue > 0) {
            if ($serviceFeeType == 'fixed') {
                $serviceFee = $serviceFeeValue;
            } else {
                $serviceFee = $totalAmount * ($serviceFeeValue / 100);
            }
        }

        $grandTotal = $totalAmount + $serviceFee;

        // Upload Proof
        $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        $order = Order::create([
            'user_id' => $landingPage->user_id,
            'landing_page_id' => $landingPage->id,
            'product_id' => $product->id,
            'quantity' => $qty,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'total_amount' => $grandTotal,
            'payment_proof_path' => $proofPath,
            'status' => 'pending',
        ]);

        // Clear voucher
        session()->forget('applied_voucher');

        return redirect()->route('public.success', ['slug' => $slug])->with('order_id', $order->id);
    }

    public function success(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();
        return view('public.success', compact('slug', 'landingPage'));
    }

    public function submitReview(Request $request, $slug)
    {
        $landingPage = LandingPage::where('slug', $slug)->firstOrFail();

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_role' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|max:1000'
        ]);

        $landingPage->reviews()->create([
            'customer_name' => $request->customer_name,
            'customer_role' => $request->customer_role,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_approved' => true // or depending on setting
        ]);


        return redirect()->route('public.show', $slug)->with('review_success', 'Terima kasih atas review Anda!');
    }
}
