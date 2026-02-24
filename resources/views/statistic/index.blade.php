<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analytics Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Core Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Revenue -->
                <div class="bg-indigo-600 rounded-3xl p-6 text-white shadow-xl shadow-indigo-100 flex flex-col justify-between h-40 relative overflow-hidden">
                    <svg class="absolute -right-4 -top-4 w-24 h-24 text-white/10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                    <div class="relative z-10">
                        <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest mb-1">Total Revenue</p>
                        <h4 class="text-2xl font-black">Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                    </div>
                    <div class="relative z-10 flex items-center gap-1 text-[10px] font-bold {{ $revenueGrowth >= 0 ? 'text-emerald-300' : 'text-red-300' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $revenueGrowth >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"/></svg>
                        {{ abs(round($revenueGrowth, 1)) }}% from last week
                    </div>
                </div>

                <!-- Conversion Rate -->
                <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-6 flex flex-col justify-between h-40">
                    <div>
                        <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Conversion Rate</p>
                        <h4 class="text-3xl font-black text-gray-900">{{ number_format($conversionRate, 1) }}%</h4>
                    </div>
                    <div class="flex items-center gap-1 text-[10px] font-bold {{ $orderGrowth >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                        {{ abs(round($orderGrowth, 1)) }}% order growth
                    </div>
                </div>

                <!-- Visits -->
                <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-6 flex flex-col justify-between h-40">
                    <div>
                        <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Visits</p>
                        <h4 class="text-3xl font-black text-gray-900">{{ number_format($totalVisits) }}</h4>
                    </div>
                    <div class="flex items-center gap-1 text-[10px] font-bold text-indigo-500">
                        Unique page views
                    </div>
                </div>

                <!-- Interaction -->
                <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-6 flex flex-col justify-between h-40">
                    <div>
                        <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Interactions</p>
                        <h4 class="text-3xl font-black text-gray-900">{{ number_format($totalClicks + $socialClicks) }}</h4>
                    </div>
                    <div class="flex items-center gap-1 text-[10px] font-bold text-pink-500">
                        Button & Social clicks
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Revenue Chart -->
                <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Revenue Trend (30d)</h3>
                    </div>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Visits Chart -->
                <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Traffic Trend (30d)</h3>
                    </div>
                    <div class="h-64">
                        <canvas id="visitsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Intermediate Section: Top LPs & Payment Methods -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Landing Pages -->
                <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Most Visited Landing Pages</h3>
                    <div class="space-y-4">
                        @forelse($topLPs as $lp)
                            <div class="flex justify-between items-center p-4 rounded-2xl bg-gray-50/50 border border-gray-100">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $lp->title }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono">/lp/{{ $lp->slug }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-indigo-600">{{ number_format($lp->visits) }}</p>
                                    <p class="text-[9px] font-black text-gray-400 uppercase">Views</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-400 italic text-sm">No traffic data yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Payment Method Stats -->
                <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Sales by Payment Method</h3>
                    <div class="h-64">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Top Products & Interaction breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Top Products -->
                <div class="lg:col-span-2 bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Best Selling Products</h3>
                    @if($topProducts->isEmpty())
                        <div class="py-12 text-center text-gray-400 italic text-sm">No sales data recorded.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                    <tr>
                                        <th class="px-4 py-3">Product Name</th>
                                        <th class="px-4 py-3 text-center">Orders</th>
                                        <th class="px-4 py-3 text-right">Total Revenue</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($topProducts as $product)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-4 py-4 font-bold text-gray-900">{{ $product->name }}</td>
                                            <td class="px-4 py-4 text-center font-mono text-gray-500">{{ number_format($product->total_orders) }}</td>
                                            <td class="px-4 py-4 text-right font-black text-indigo-600">Rp {{ number_format($product->revenue, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Extra Metrics -->
                <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white p-8 flex flex-col space-y-6">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Interaction Stats</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase">Button Clicks</p>
                                <p class="text-xl font-black text-gray-900">{{ number_format($totalClicks) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">{{ $totalVisits > 0 ? round(($totalClicks / $totalVisits) * 100, 1) : 0 }}% CTR</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase">Social Shares</p>
                                <p class="text-xl font-black text-gray-900">{{ number_format($socialClicks) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-bold text-pink-600 bg-pink-50 px-2 py-0.5 rounded-full">{{ $totalVisits > 0 ? round(($socialClicks / $totalVisits) * 100, 1) : 0 }}% SR</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <div class="bg-indigo-50 rounded-2xl p-4">
                            <p class="text-[10px] font-black text-indigo-800 uppercase mb-1">Business Insight</p>
                            <p class="text-[11px] text-indigo-600 leading-relaxed font-medium">
                                @if($conversionRate < 5)
                                    Konversi Anda cukup rendah. Coba tambahkan testimoni atau diskon terbatas untuk meningkatkan urgency.
                                @elseif($conversionRate >= 5 && $conversionRate < 15)
                                    Performa Landing Page stabil. Teruskan optimasi pada copywriting untuk hasil lebih maksimal.
                                @else
                                    Luar biasa! Konversi Anda sangat tinggi. Fokus pada mendatangkan lebih banyak traffic.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ChartJS Scripts -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Options for charts
            const baseOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false, drawBorder: false }, ticks: { display: false } },
                    x: { grid: { display: false, drawBorder: false }, ticks: { font: { size: 9 }, color: '#9ca3af' } }
                }
            };

            // Revenue Chart
            const revCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
                    datasets: [{
                        label: 'Daily Revenue',
                        data: {!! json_encode($dailyRevenue->pluck('total')) !!},
                        borderColor: '#4f46e5',
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(79, 70, 229, 0)');
                            gradient.addColorStop(1, 'rgba(79, 70, 229, 0.2)');
                            return gradient;
                        },
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 5
                    }]
                },
                options: baseOptions
            });

            // Visits Chart
            const visitCtx = document.getElementById('visitsChart').getContext('2d');
            new Chart(visitCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($dailyVisits->pluck('date')) !!},
                    datasets: [{
                        label: 'Visits',
                        data: {!! json_encode($dailyVisits->pluck('total')) !!},
                        backgroundColor: '#ec4899',
                        borderRadius: 6
                    }]
                },
                options: baseOptions
            });

            // Payment Chart (Pie)
            const payCtx = document.getElementById('paymentChart').getContext('2d');
            new Chart(payCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($paymentMethodStats->pluck('payment_method')) !!},
                    datasets: [{
                        data: {!! json_encode($paymentMethodStats->pluck('total')) !!},
                        backgroundColor: ['#4f46e5', '#ec4899', '#10b981', '#f59e0b', '#6366f1'],
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            display: true, 
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 10, weight: 'bold' }
                            }
                        } 
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
