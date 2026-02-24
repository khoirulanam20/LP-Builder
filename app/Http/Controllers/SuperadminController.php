<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\LandingPage;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;

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
            'service_fee_type'        => 'required|in:fixed,percentage',
            'service_fee_amount'      => 'required|numeric|min:0',
            'midtrans_environment'    => 'required|in:sandbox,production',
            'midtrans_server_key'     => 'nullable|string',
            'midtrans_client_key'     => 'nullable|string',
        ]);

        SystemSetting::updateOrCreate(['key' => 'service_fee_type'],     ['value' => $request->service_fee_type]);
        SystemSetting::updateOrCreate(['key' => 'service_fee_amount'],   ['value' => $request->service_fee_amount]);
        SystemSetting::updateOrCreate(['key' => 'midtrans_environment'], ['value' => $request->midtrans_environment]);

        if ($request->filled('midtrans_server_key')) {
            SystemSetting::updateOrCreate(['key' => 'midtrans_server_key'], ['value' => $request->midtrans_server_key]);
        }
        if ($request->filled('midtrans_client_key')) {
            SystemSetting::updateOrCreate(['key' => 'midtrans_client_key'], ['value' => $request->midtrans_client_key]);
        }

        return redirect()->back()->with('status', 'System settings updated successfully.');
    }

    public function updateSiteProfile(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:100',
            'site_logo' => 'nullable|image|max:2048',
        ]);

        SystemSetting::updateOrCreate(['key' => 'site_name'], ['value' => $request->site_name]);

        if ($request->hasFile('site_logo')) {
            // Delete old logo
            $oldLogo = SystemSetting::where('key', 'site_logo')->value('value');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('site_logo')->store('site', 'public');
            SystemSetting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
        }

        return redirect()->back()->with('status', 'Site profile updated successfully.');
    }

    public function usersIndex()
    {
        $users = User::where('role', 'user')->orderBy('created_at', 'desc')->paginate(20);
        return view('superadmin.users.index', compact('users'));
    }

    public function userShow($id)
    {
        $user = User::with(['paymentMethods', 'landingPages'])->findOrFail($id);
        
        // Paginate orders separately
        $orders = $user->orders()->orderBy('created_at', 'desc')->paginate(15);
        
        // Calculate earnings
        $totalOrders = $user->orders()->whereIn('status', ['completed', 'verified'])->count();
        $totalEarnings = $user->orders()->whereIn('status', ['completed', 'verified'])->sum('total_amount');
        
        return view('superadmin.users.show', compact('user', 'orders', 'totalOrders', 'totalEarnings'));
    }
}
