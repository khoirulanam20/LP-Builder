<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnalyticTracker;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Core Totals
        $totalSales = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->sum('total_amount');

        $totalOrdersCount = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->count();

        $totalVisits = AnalyticTracker::where('user_id', $user->id)
            ->where('type', 'visit')->count();

        $totalClicks = AnalyticTracker::where('user_id', $user->id)
            ->where('type', 'click')->count();

        $socialClicks = AnalyticTracker::where('user_id', $user->id)
            ->where('type', 'social')->count();

        // 2. Weekly Growth
        $thisWeekRevenue = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->where('created_at', '>=', now()->startOfWeek())
            ->sum('total_amount');
        
        $lastWeekRevenue = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->sum('total_amount');
        
        $revenueGrowth = $lastWeekRevenue > 0 ? (($thisWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100 : 100;

        $thisWeekOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();
        
        $lastWeekOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();
        
        $orderGrowth = $lastWeekOrders > 0 ? (($thisWeekOrders - $lastWeekOrders) / $lastWeekOrders) * 100 : 100;

        // 3. Conversion Rate
        $conversionRate = $totalVisits > 0 ? ($totalOrdersCount / $totalVisits) * 100 : 0;

        // 4. Charts Data (Daily - Last 30 Days)
        $dailyRevenue = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dailyVisits = AnalyticTracker::where('user_id', $user->id)
            ->where('type', 'visit')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 5. Top Landing Pages
        $topLPs = AnalyticTracker::where('analytic_trackers.user_id', $user->id)
            ->where('type', 'visit')
            ->join('landing_pages', 'analytic_trackers.landing_page_id', '=', 'landing_pages.id')
            ->select('landing_pages.title', 'landing_pages.slug', DB::raw('count(*) as visits'))
            ->groupBy('landing_pages.id', 'landing_pages.title', 'landing_pages.slug')
            ->orderByDesc('visits')
            ->limit(5)
            ->get();

        // 6. Top 5 Products
        $topProducts = Order::where('orders.user_id', $user->id)
            ->whereIn('orders.status', ['completed', 'verified'])
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('count(orders.id) as total_orders'), DB::raw('sum(orders.total_amount) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();

        // 7. Sales by Payment Method
        $paymentMethodStats = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'verified'])
            ->select('payment_method', DB::raw('count(*) as total'))
            ->groupBy('payment_method')
            ->get();

        return view('statistic.index', compact(
            'totalSales', 'totalVisits', 'totalClicks', 'socialClicks', 
            'topProducts', 'conversionRate', 'dailyRevenue', 'dailyVisits',
            'revenueGrowth', 'orderGrowth', 'topLPs', 'paymentMethodStats'
        ));
    }
}
