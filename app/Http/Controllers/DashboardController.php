<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnalyticTracker;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $pendingOrders = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        // Chart data: last 7 days
        $days = collect(range(6, 0))->map(fn($i) => Carbon::now()->subDays($i));
        $dayLabels = $days->map(fn($d) => $d->format('d M'))->toArray();

        $ordersPerDay = $days->map(function($day) use ($user) {
            return Order::where('user_id', $user->id)
                ->whereDate('created_at', $day->toDateString())
                ->count();
        })->toArray();

        $revenuePerDay = $days->map(function($day) use ($user) {
            return (float) Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('created_at', $day->toDateString())
                ->sum('total_amount');
        })->toArray();

        $visitsPerDay = $days->map(function($day) use ($user) {
            return AnalyticTracker::where('user_id', $user->id)
                ->where('type', 'visit')
                ->whereDate('created_at', $day->toDateString())
                ->count();
        })->toArray();
            
        return view('dashboard', compact(
            'totalVisits', 'totalClicks', 'totalSales', 'totalOrders',
            'pendingOrders', 'dayLabels', 'ordersPerDay', 'revenuePerDay', 'visitsPerDay'
        ));
    }
}

