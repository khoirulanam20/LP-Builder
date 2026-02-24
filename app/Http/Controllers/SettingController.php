<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $paymentMethods = PaymentMethod::where('user_id', $user->id)->get();
        return view('setting.index', compact('paymentMethods', 'user'));
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
        ]);

        PaymentMethod::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('setting.index')->with('status', 'Payment method added successfully.');
    }

    public function destroyPayment(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::where('user_id', $request->user()->id)->findOrFail($id);
        $paymentMethod->delete();

        return redirect()->route('setting.index')->with('status', 'Payment method removed.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'custom_email_message' => 'nullable|string'
        ]);

        $request->user()->update([
            'custom_email_message' => $request->custom_email_message
        ]);

        return redirect()->route('setting.index')->with('status', 'Custom email message updated.');
    }
}
