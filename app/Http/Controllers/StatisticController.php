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

        $totalSales = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('total_amount');

        $totalVisits = AnalyticTracker::where('user_id', $user->id)
            ->where('type', 'visit')->count();

        $totalClicks = AnalyticTracker::where('user_id', $user->id)
            ->where('type', 'click')->count();

        $socialClicks = AnalyticTracker::where('user_id', $user->id)
            ->where('type', 'social')->count();

        // Top 5 products by completed orders
        $topProducts = Order::where('orders.user_id', $user->id)
            ->where('orders.status', 'completed')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('count(orders.id) as total_orders'), DB::raw('sum(orders.total_amount) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();

        return view('statistic.index', compact(
            'totalSales', 'totalVisits', 'totalClicks', 'socialClicks', 'topProducts'
        ));
    }
}
