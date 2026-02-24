<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnalyticTracker;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Summing statistics for all landing pages owned by the user
        $totalVisits = AnalyticTracker::where('user_id', $user->id)
            ->where('type', 'visit')->count();
            
        $totalClicks = AnalyticTracker::where('user_id', $user->id)
            ->where('type', 'click')->count();
            
        $totalSales = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_amount');
            
        $totalOrders = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
            
        return view('dashboard', compact('totalVisits', 'totalClicks', 'totalSales', 'totalOrders'));
    }
}
