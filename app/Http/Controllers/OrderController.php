<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\SystemSetting;
use Midtrans\Config as MidtransConfig;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;
use App\Traits\HandlesMidtrans;

class OrderController extends Controller
{
    use HandlesMidtrans;

    public function index(Request $request)
    {
        $user = $request->user();
        
        $orders = Order::with(['product', 'landingPage'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('orders.index', compact('orders'));
    }

    public function verify(Request $request, $id)
    {
        $user = $request->user();
        $order = Order::where('user_id', $user->id)->findOrFail($id);
        
        // If order from midtrans, check if it's already completed
        if ($order->midtrans_order_id && $order->status !== 'completed') {
            return redirect()->back()->with('error', 'Pesanan belum dibayar di Midtrans.');
        }

        $request->validate([
            'status' => 'required|in:verified,completed,rejected'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send email if status is changed to verified
        if ($request->status === 'verified' && $oldStatus !== 'verified' && $order->customer_email) {
            \Illuminate\Support\Facades\Mail::to($order->customer_email)
                ->send(new \App\Mail\OrderCompleted($order));
        }

        return redirect()->route('orders.index')->with('status', 'Order status updated to ' . $request->status);
    }

    public function syncStatus(Request $request, $id)
    {
        $user  = $request->user();
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        if (!$order->midtrans_order_id) {
            return redirect()->back()->with('error', 'Pesanan ini tidak memiliki ID Midtrans.');
        }

        $newStatus = $this->syncOrderWithMidtrans($order);

        if ($newStatus) {
            return redirect()->back()->with('status', 'Status pesanan disinkronkan: ' . $newStatus);
        }

        return redirect()->back()->with('error', 'Gagal sinkronisasi dengan Midtrans.');
    }
}
