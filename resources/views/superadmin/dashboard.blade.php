<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Superadmin Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">{{ session('status') }}</div>
            @endif

            <!-- ── Analytics ── -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/80 backdrop-blur-sm shadow-sm rounded-2xl border border-white p-6">
                    <div class="flex items-center text-gray-600 mb-2">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="font-semibold text-sm">Total Users</span>
                    </div>
                    <div class="text-3xl font-black text-gray-900">{{ number_format($totalUsers) }}</div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm shadow-sm rounded-2xl border border-white p-6">
                    <div class="flex items-center text-gray-600 mb-2">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span class="font-semibold text-sm">Total Orders</span>
                    </div>
                    <div class="text-3xl font-black text-gray-900">{{ number_format($totalOrders) }}</div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm shadow-sm rounded-2xl border border-white p-6">
                    <div class="flex items-center text-gray-600 mb-2">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-semibold text-sm">Total Revenue</span>
                    </div>
                    <div class="text-3xl font-black text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- ── Two Column Settings ── -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Midtrans + Service Fee -->
                <div class="bg-white/80 backdrop-blur-sm shadow-sm rounded-2xl border border-white p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Midtrans & Biaya Layanan</h3>
                            <p class="text-xs text-gray-500">Konfigurasi payment gateway & service fee</p>
                        </div>
                    </div>

                    <form action="{{ route('superadmin.settings.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <!-- Midtrans -->
                        <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 space-y-3">
                            <p class="text-xs font-bold text-blue-700 uppercase tracking-wide">🔑 Midtrans Credentials</p>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Environment</label>
                                <select name="midtrans_environment" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="sandbox" {{ ($settings['midtrans_environment'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                    <option value="production" {{ ($settings['midtrans_environment'] ?? '') == 'production' ? 'selected' : '' }}>Production (Live)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Server Key</label>
                                <input type="text" name="midtrans_server_key"
                                    value="{{ $settings['midtrans_server_key'] ?? '' }}"
                                    placeholder="SB-Mid-server-xxxx atau Mid-server-xxxx"
                                    class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Client Key</label>
                                <input type="text" name="midtrans_client_key"
                                    value="{{ $settings['midtrans_client_key'] ?? '' }}"
                                    placeholder="SB-Mid-client-xxxx atau Mid-client-xxxx"
                                    class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500 font-mono">
                            </div>
                        </div>

                        <!-- Service Fee -->
                        <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 space-y-3">
                            <p class="text-xs font-bold text-indigo-700 uppercase tracking-wide">💳 Biaya Layanan</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Biaya</label>
                                    <select name="service_fee_type" class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="fixed" {{ ($settings['service_fee_type'] ?? '') == 'fixed' ? 'selected' : '' }}>Nominal Tetap (IDR)</option>
                                        <option value="percentage" {{ ($settings['service_fee_type'] ?? '') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Jumlah / Nilai</label>
                                    <input type="number" step="0.01" name="service_fee_amount"
                                        value="{{ $settings['service_fee_amount'] ?? 0 }}"
                                        class="w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition active:scale-95 text-sm">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Site Profile -->
                <div class="bg-white/80 backdrop-blur-sm shadow-sm rounded-2xl border border-white p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Profil Website</h3>
                            <p class="text-xs text-gray-500">Nama aplikasi & logo navbar</p>
                        </div>
                    </div>

                    <form action="{{ route('superadmin.site-profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Website</label>
                            <input type="text" name="site_name"
                                value="{{ $settings['site_name'] ?? config('app.name') }}"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-emerald-500 focus:ring-emerald-500"
                                placeholder="LP Builder">
                            <p class="text-[10px] text-gray-400 mt-1">Ditampilkan di navbar dan tab browser.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Logo Website</label>
                            @if(!empty($settings['site_logo']))
                                <div class="mb-2 flex items-center gap-3">
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="h-12 w-auto rounded-xl border border-gray-200 p-1 bg-white shadow-sm">
                                    <span class="text-xs text-gray-400">Logo saat ini</span>
                                </div>
                            @endif
                            <input type="file" name="site_logo" accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-1">PNG/SVG transparan direkomendasikan, maks 2MB.</p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition active:scale-95 text-sm">
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ── User Approvals ── -->
            <div class="bg-white/80 backdrop-blur-sm shadow-sm rounded-2xl border border-white p-6">
                <h3 class="text-base font-bold text-gray-800 mb-4">👥 User Approvals</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-3">User</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Registered At</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="bg-white border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ $user->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        @if($user->is_approved)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-200">Approved</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-200">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <form action="{{ route('superadmin.users.approve', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="{{ $user->is_approved ? 'text-red-600' : 'text-indigo-600' }} font-bold hover:underline">
                                                    {{ $user->is_approved ? 'Revoke' : 'Approve' }}
                                                </button>
                                            </form>
                                            <a href="{{ route('superadmin.users.show', $user->id) }}" class="text-gray-900 font-black hover:text-indigo-600">Detail</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $users->links() }}</div>
            </div>

        </div>
    </div>
</x-app-layout>
