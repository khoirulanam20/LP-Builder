<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.users.index') }}" class="p-2 bg-white rounded-xl shadow-sm hover:bg-gray-50 transition border border-gray-100">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Details: {{ $user->name }}</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- User Basic Info & Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="lg:col-span-1 bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-6">
                    <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100">
                        <div class="w-20 h-20 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 mb-4 shadow-inner">
                            <span class="text-3xl font-black">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <h3 class="text-xl font-black text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>
                        @if($user->is_approved)
                            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-widest">Active Member</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[10px] font-black bg-yellow-50 text-yellow-600 border border-yellow-100 uppercase tracking-widest">Pending Approval</span>
                        @endif
                    </div>
                    <div class="py-6 space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">Joined Date</span>
                            <span class="text-gray-900 font-bold">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">Total Landing Pages</span>
                            <span class="text-gray-900 font-bold">{{ $user->landingPages->count() }}</span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <form action="{{ route('superadmin.users.approve', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 rounded-2xl text-[11px] font-black uppercase tracking-widest transition {{ $user->is_approved ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-100' }}">
                                {{ $user->is_approved ? 'Disable User Account' : 'Enable User Account' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Earnings Stats -->
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-200 relative overflow-hidden flex flex-col justify-between">
                        <svg class="absolute -right-4 -top-4 w-32 h-32 text-white/10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <div class="relative z-10">
                            <p class="text-indigo-100 text-xs font-black uppercase tracking-widest mb-1">Total Penjualan</p>
                            <h4 class="text-4xl font-black">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</h4>
                        </div>
                        <div class="relative z-10 pt-4 mt-auto">
                            <div class="flex items-center gap-2 text-indigo-100 text-[10px] font-bold">
                                <span class="bg-white/20 px-2 py-0.5 rounded">Success Orders</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8 flex flex-col justify-between">
                        <div>
                            <p class="text-gray-400 text-xs font-black uppercase tracking-widest mb-1">Volume Pesanan</p>
                            <h4 class="text-4xl font-black text-gray-900">{{ number_format($totalOrders) }} <span class="text-sm font-medium text-gray-400 uppercase">Orders</span></h4>
                        </div>
                        <div class="pt-4 mt-auto">
                            <div class="flex items-center gap-2 text-indigo-600 text-[10px] font-bold">
                                <span>Rata-rata order: Rp {{ $totalOrders > 0 ? number_format($totalEarnings / $totalOrders, 0, ',', '.') : 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Tabs/Sections -->
            <div x-data="{ activeTab: '{{ request()->has('page') ? 'orders' : 'orders' }}' }" class="space-y-6">
                <!-- Tab Navigation -->
                <div class="flex gap-2 p-1 bg-gray-100/50 rounded-2xl w-fit border border-gray-100">
                    <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition">Order History</button>
                    <button @click="activeTab = 'payments'" :class="activeTab === 'payments' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition">Payment Methods</button>
                    <button @click="activeTab = 'email'" :class="activeTab === 'email' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition">Email Delivery</button>
                </div>

                <!-- Orders Section -->
                <div x-show="activeTab === 'orders'" class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8 overflow-hidden">
                    <h3 class="text-base font-black text-gray-900 mb-6 flex items-center gap-2 uppercase tracking-wide">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        All Orders History
                    </h3>
                    <div class="overflow-x-auto rounded-2xl border border-gray-50 mb-6">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50/80 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Waktu & Ref</th>
                                    <th class="px-6 py-4">Pembeli</th>
                                    <th class="px-6 py-4">Total</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-xs font-bold text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                                            <div class="text-[10px] text-gray-400 font-mono">{{ $order->midtrans_order_id ?? $order->id }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs font-bold text-gray-900">{{ $order->customer_name }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $order->customer_email }}</div>
                                        </td>
                                        <td class="px-6 py-4 font-black text-indigo-600 text-xs">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $cls = match($order->status) {
                                                    'verified'  => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'pending'   => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                                    'failed'    => 'bg-red-50 text-red-600 border-red-100',
                                                    default     => 'bg-gray-50 text-gray-500 border-gray-100'
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full text-[9px] font-black border uppercase tracking-widest {{ $cls }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">Belum ada pesanan masuk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>

                <!-- Payment Methods Section -->
                <div x-show="activeTab === 'payments'" class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8">
                    <h3 class="text-base font-black text-gray-900 mb-6 flex items-center gap-2 uppercase tracking-wide">
                        <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Manual Payment Accounts
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($user->paymentMethods as $pm)
                            <div class="p-5 rounded-2xl border border-gray-100 bg-gray-50/50">
                                <h4 class="font-black text-gray-900 text-sm mb-1 uppercase tracking-tight">{{ $pm->name }}</h4>
                                <p class="text-xs text-indigo-600 font-mono font-bold">{{ $pm->bank_name }} - {{ $pm->account_number }}</p>
                                @if($pm->instructions)
                                    <p class="text-[10px] text-gray-400 mt-3 pt-3 border-t border-gray-100 italic">"{{ $pm->instructions }}"</p>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-2 py-12 text-center text-gray-400 border-2 border-dashed border-gray-100 rounded-3xl">Pangguna belum menambahkan metode pembayaran manual.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Custom Email Message Section -->
                <div x-show="activeTab === 'email'" class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8">
                    <h3 class="text-base font-black text-gray-900 mb-6 flex items-center gap-2 uppercase tracking-wide">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Custom Delivery Email
                    </h3>
                    @if($user->custom_email_message)
                        <div class="bg-indigo-50/50 border border-indigo-100 p-6 rounded-3xl">
                            <p class="text-xs text-indigo-800 leading-relaxed whitespace-pre-wrap font-medium">
                                {{ $user->custom_email_message }}
                            </p>
                        </div>
                    @else
                        <div class="py-12 text-center text-gray-400 italic">Pengguna belum mengatur pesan email custom.</div>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
