<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Validation\Rule;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $vouchers = Voucher::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        
        return view('vouchers.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('vouchers')],
            'discount_amount' => 'required|numeric|min:0',
            'discount_type' => 'required|in:fixed,percentage',
        ]);

        Voucher::create([
            'user_id' => $user->id,
            'code' => strtoupper($request->code),
            'discount_amount' => $request->discount_amount,
            'discount_type' => $request->discount_type,
        ]);

        return redirect()->route('vouchers.index')->with('status', 'Voucher created successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $voucher = Voucher::where('user_id', $request->user()->id)->findOrFail($id);
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('status', 'Voucher deleted.');
    }
}
