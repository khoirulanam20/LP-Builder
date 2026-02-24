<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-6 overflow-hidden">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            Daftar Pesanan
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Pantau dan verifikasi pesanan masuk lewat Midtrans.</p>
                    </div>
                </div>
                
                @if($orders->isEmpty())
                    <div class="text-center py-12">
                        <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        </div>
                        <p class="text-gray-400 text-sm">Belum ada pesanan masuk.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50">
                                    <th class="px-6 py-4">Waktu & Ref</th>
                                    <th class="px-6 py-4">Pembeli</th>
                                    <th class="px-6 py-4">Produk</th>
                                    <th class="px-6 py-4">Total</th>
                                    <th class="px-6 py-4">Status Midtrans</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-xs font-semibold text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                                            <div class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $order->midtrans_order_id ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-xs font-bold text-gray-900">{{ $order->customer_name ?? 'N/A' }}</div>
                                            <div class="text-[10px] text-gray-500">{{ $order->customer_email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs text-gray-700 font-medium truncate max-w-[150px]">{{ optional($order->product)->name ?? 'Unknown' }}</div>
                                            <div class="text-[10px] text-gray-400 mt-0.5">Qty: {{ $order->quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-xs font-black text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'pending'   => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                                    'verified'  => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                    'completed' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                    'failed'    => 'bg-red-50 text-red-700 border-red-100',
                                                    'rejected'  => 'bg-gray-100 text-gray-600 border-gray-200',
                                                ];
                                                $cls = $statusClasses[$order->status] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                            @endphp
                                            <div class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $cls }} uppercase tracking-wider shadow-sm">
                                                {{ $order->status }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <!-- Sync Button -->
                                                @if($order->midtrans_order_id && in_array($order->status, ['pending', 'failed']))
                                                    <form method="POST" action="{{ route('orders.sync', $order->id) }}">
                                                        @csrf
                                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Sync Status Midtrans">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Action Buttons -->
                                                @if($order->status == 'completed')
                                                    <form method="POST" action="{{ route('orders.verify', $order->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="verified">
                                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-bold py-1.5 px-3 rounded-lg shadow-md transition active:scale-95">
                                                            VERIFIKASI
                                                        </button>
                                                    </form>
                                                @elseif($order->status == 'verified')
                                                    <div class="text-[10px] font-bold text-emerald-600 italic flex items-center justify-end gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                        Terverifikasi
                                                    </div>
                                                @elseif($order->status == 'pending')
                                                    <div class="text-[10px] font-bold text-yellow-600/50 italic">Menunggu Bayar</div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
