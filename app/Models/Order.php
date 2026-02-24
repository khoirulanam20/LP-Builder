<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'midtrans_response' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function landingPage() {
        return $this->belongsTo(LandingPage::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
