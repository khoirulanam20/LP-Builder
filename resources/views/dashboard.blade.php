<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- ── Stat Cards Row ── -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">

                <!-- Total Revenue -->
                <div class="col-span-2 lg:col-span-1 bg-gradient-to-br from-indigo-600 to-indigo-500 text-white rounded-3xl p-6 shadow-xl flex flex-col justify-between">
                    <div class="flex items-center gap-2 mb-3 opacity-80">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-bold uppercase tracking-wider">Total Revenue</span>
                    </div>
                    <div class="text-2xl font-black leading-tight">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                    <div class="text-xs opacity-60 mt-1">Dari pesanan selesai</div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 p-6 flex flex-col justify-between">
                    <div class="flex items-center gap-2 mb-3 text-purple-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Selesai</span>
                    </div>
                    <div class="text-3xl font-black text-gray-900">{{ number_format($totalOrders) }}</div>
                    <div class="text-xs text-gray-400 mt-1">Pesanan sukses</div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 p-6 flex flex-col justify-between">
                    <div class="flex items-center gap-2 mb-3 text-amber-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Pending</span>
                    </div>
                    <div class="text-3xl font-black text-gray-900">{{ number_format($pendingOrders) }}</div>
                    <div class="text-xs text-gray-400 mt-1">Menunggu verifikasi</div>
                </div>

                <!-- Visits -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 p-6 flex flex-col justify-between">
                    <div class="flex items-center gap-2 mb-3 text-emerald-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Kunjungan</span>
                    </div>
                    <div class="text-3xl font-black text-gray-900">{{ number_format($totalVisits) }}</div>
                    <div class="text-xs text-gray-400 mt-1">Total halaman dilihat</div>
                </div>

                <!-- Clicks -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 p-6 flex flex-col justify-between">
                    <div class="flex items-center gap-2 mb-3 text-rose-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/></svg>
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Klik Produk</span>
                    </div>
                    <div class="text-3xl font-black text-gray-900">{{ number_format($totalClicks) }}</div>
                    <div class="text-xs text-gray-400 mt-1">Total interaksi</div>
                </div>
            </div>

            <!-- ── Charts Row ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Orders + Revenue Chart (2/3 width) -->
                <div class="lg:col-span-2 bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800 text-base">Pesanan & Revenue</h3>
                            <p class="text-xs text-gray-400 mt-0.5">7 hari terakhir</p>
                        </div>
                        <div class="flex items-center gap-3 text-xs">
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-indigo-500 inline-block"></span> Pesanan</span>
                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span> Revenue</span>
                        </div>
                    </div>
                    <div class="relative" style="height:220px;">
                        <canvas id="ordersRevenueChart"></canvas>
                    </div>
                </div>

                <!-- Visits Chart (1/3 width) -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 p-6">
                    <div class="mb-4">
                        <h3 class="font-bold text-gray-800 text-base">Kunjungan Harian</h3>
                        <p class="text-xs text-gray-400 mt-0.5">7 hari terakhir</p>
                    </div>
                    <div class="relative" style="height:220px;">
                        <canvas id="visitsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- ── Quick Links Row ── -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('orders.index') }}" class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 p-5 flex items-center gap-4 hover:shadow-2xl hover:-translate-y-1 transition group">
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm">Kelola Pesanan</div>
                        <div class="text-xs text-gray-400">Verifikasi pembayaran</div>
                    </div>
                </a>
                <a href="{{ route('my-lp.index') }}" class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 p-5 flex items-center gap-4 hover:shadow-2xl hover:-translate-y-1 transition group">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm">Edit Landing Page</div>
                        <div class="text-xs text-gray-400">Produk & konten</div>
                    </div>
                </a>
                <a href="{{ route('statistic.index') }}" class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 p-5 flex items-center gap-4 hover:shadow-2xl hover:-translate-y-1 transition group">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800 text-sm">Statistik Lengkap</div>
                        <div class="text-xs text-gray-400">Analitik mendalam</div>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const labels  = {!! json_encode($dayLabels) !!};
        const orders  = {!! json_encode($ordersPerDay) !!};
        const revenue = {!! json_encode($revenuePerDay) !!};
        const visits  = {!! json_encode($visitsPerDay) !!};

        // Orders + Revenue chart
        const ctx1 = document.getElementById('ordersRevenueChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Pesanan',
                        data: orders,
                        backgroundColor: 'rgba(99, 102, 241, 0.7)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderRadius: 6,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Revenue (Rp)',
                        data: revenue,
                        type: 'line',
                        borderColor: 'rgba(52, 211, 153, 1)',
                        backgroundColor: 'rgba(52, 211, 153, 0.1)',
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(52, 211, 153, 1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: false } },
                scales: {
                    y:  { position: 'left',  beginAtZero: true, ticks: { stepSize: 1, color: '#6366f1' }, grid: { color: '#f1f5f9' } },
                    y1: { position: 'right', beginAtZero: true, ticks: { color: '#34d399', callback: v => 'Rp ' + v.toLocaleString('id-ID') }, grid: { drawOnChartArea: false } },
                    x:  { ticks: { color: '#94a3b8' }, grid: { display: false } }
                }
            }
        });

        // Visits chart
        const ctx2 = document.getElementById('visitsChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Kunjungan',
                    data: visits,
                    borderColor: 'rgba(251, 146, 60, 1)',
                    backgroundColor: 'rgba(251, 146, 60, 0.1)',
                    borderWidth: 2.5,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgba(251, 146, 60, 1)',
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8' }, grid: { color: '#f1f5f9' } },
                    x: { ticks: { color: '#94a3b8' }, grid: { display: false } }
                }
            }
        });
    </script>
</x-app-layout>
