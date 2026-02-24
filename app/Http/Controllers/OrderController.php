<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
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
        
        $request->validate([
            'status' => 'required|in:verified,completed,rejected'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send email if status is changed to completed
        if ($request->status === 'completed' && $oldStatus !== 'completed' && $order->customer_email) {
            \Illuminate\Support\Facades\Mail::to($order->customer_email)
                ->send(new \App\Mail\OrderCompleted($order));
        }

        return redirect()->route('orders.index')->with('status', 'Order status updated to ' . $request->status);
    }
}
