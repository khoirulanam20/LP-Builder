<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function appearance() {
        return $this->hasOne(Appearance::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function analyticTrackers() {
        return $this->hasMany(AnalyticTracker::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
