<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\LandingPage;

class AnalyticTracker extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function landingPage() {
        return $this->belongsTo(LandingPage::class);
    }
}
