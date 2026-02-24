<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Sales -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">Total Sales</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">RP {{ number_format($totalSales, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">Total Orders</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalOrders) }}</div>
                    </div>
                </div>

                <!-- User Visits -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">User Visits</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalVisits) }}</div>
                    </div>
                </div>

                <!-- User Clicks -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 truncate">User Clicks</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($totalClicks) }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Welcome back to your dashboard!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
