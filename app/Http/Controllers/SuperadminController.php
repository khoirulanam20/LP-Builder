<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Order;
use App\Models\LandingPage;
use App\Models\SystemSetting;

class SuperadminController extends Controller
{
    public function dashboard()
    {
        $users = User::where('role', 'user')->orderBy('created_at', 'desc')->paginate(10);
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        $settings = SystemSetting::pluck('value', 'key');

        return view('superadmin.dashboard', compact('users', 'totalUsers', 'totalOrders', 'totalRevenue', 'settings'));
    }

    public function approveUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = !$user->is_approved;
        $user->save();

        $status = $user->is_approved ? 'approved' : 'unapproved';
        return redirect()->back()->with('status', "User successfully {$status}.");
    }
    public function updateSettings(Request $request)
    {
        $request->validate([
            'service_fee_type' => 'required|in:fixed,percentage',
            'service_fee_amount' => 'required|numeric|min:0',
        ]);

        SystemSetting::updateOrCreate(['key' => 'service_fee_type'], ['value' => $request->service_fee_type]);
        SystemSetting::updateOrCreate(['key' => 'service_fee_amount'], ['value' => $request->service_fee_amount]);

        return redirect()->back()->with('status', 'System settings updated successfully.');
    }
}
