<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detailed Statistics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Sales -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">Total Sales Revenue</div>
                        <div class="mt-1 text-3xl font-semibold text-indigo-600">RP {{ number_format($totalSales, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Web Visits -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">Total LP Visits</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalVisits) }}</div>
                    </div>
                </div>

                <!-- Product Clicks -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">Total Button Clicks</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalClicks) }}</div>
                    </div>
                </div>

                <!-- Social Clicks -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">Social Media Clicks</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($socialClicks) }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Top 5 Sales by Product</h3>
                    
                    @if($topProducts->isEmpty())
                        <p class="text-gray-500 text-sm">No sales data available yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Orders</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($topProducts as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($product->total_orders) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($product->revenue, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
