<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\SystemSetting;
use App\Mail\OrderCompleted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Transaction;

trait HandlesMidtrans
{
    protected function configureMidtrans()
    {
        $settings = SystemSetting::pluck('value', 'key');
        
        MidtransConfig::$serverKey    = $settings['midtrans_server_key'] ?? config('services.midtrans.server_key');
        MidtransConfig::$clientKey    = $settings['midtrans_client_key'] ?? config('services.midtrans.client_key');
        
        $env = $settings['midtrans_environment'] ?? (config('services.midtrans.is_production') ? 'production' : 'sandbox');
        MidtransConfig::$isProduction = ($env === 'production');
        
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;
    }

    public function syncOrderWithMidtrans(Order $order)
    {
        if (!$order->midtrans_order_id) return false;

        try {
            $this->configureMidtrans();
            $status = Transaction::status($order->midtrans_order_id);
            
            return $this->processStatus($order, $status->transaction_status, $status->fraud_status, $status->payment_type ?? null);
        } catch (\Exception $e) {
            Log::error('Midtrans Sync Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process Midtrans status and update order
     */
    public function processStatus(Order $order, $transactionStatus, $fraudStatus = null, $paymentType = null)
    {
        $oldStatus = $order->status;
        $order->payment_method = $paymentType ?? $order->payment_method;

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'challenge') {
                $order->status = 'pending';
            } else {
                $order->status = 'verified';
            }
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $order->status = 'failed';
        } else if ($transactionStatus === 'pending') {
            $order->status = 'pending';
        }

        $order->save();

        // Send completion email if newly verified
        if ($order->status === 'verified' && $oldStatus !== 'verified') {
            try {
                Mail::to($order->customer_email)->send(new OrderCompleted($order));
            } catch (\Exception $e) {
                Log::error('Mail send failed during sync: ' . $e->getMessage());
            }
        }

        return $order->status;
    }
}
