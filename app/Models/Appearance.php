<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appearance extends Model
{
    protected $guarded = [];

    protected $casts = [
        'social_links' => 'array',
    ];

    public function landingPage() {
        return $this->belongsTo(LandingPage::class);
    }
}
