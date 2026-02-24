<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function landingPage() {
        return $this->belongsTo(LandingPage::class);
    }

    public function addOns() {
        return $this->hasMany(AddOn::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
