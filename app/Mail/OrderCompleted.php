<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $customMessage;
    public $productName;
    public $downloadUrl;
    public $filePath;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $seller = $order->product->landingPage->user;
        $this->customMessage = $seller->custom_email_message ?? '';
        $this->productName = $order->product->name;
        
        $this->downloadUrl = $order->product->type === 'url' ? $order->product->download_url : null;
        $this->filePath = $order->product->type === 'file' ? $order->product->file_path : null;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesanan Anda: ' . $this->productName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order_completed',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
