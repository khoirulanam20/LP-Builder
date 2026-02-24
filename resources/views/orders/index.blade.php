<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-800 bg-green-100/50 backdrop-blur border border-green-200 p-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden" x-data="{ showModal: false, imageUrl: '' }">
                <div class="p-8 text-gray-900 border-b border-gray-200/50">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Orders Management
                    </h3>
                    
                    @if($orders->isEmpty())
                        <p class="text-gray-500 text-sm">No orders found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proof</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div>{{ $order->customer_name ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ optional($order->product)->name ?? 'Unknown' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($order->status == 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                @elseif($order->status == 'verified')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Verified</span>
                                                @elseif($order->status == 'completed')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                                                @elseif($order->status == 'rejected')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($order->payment_proof_path)
                                                    <button @click="imageUrl = '{{ asset('storage/' . $order->payment_proof_path) }}'; showModal = true" class="text-indigo-600 hover:text-indigo-900 flex items-center bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors font-medium">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        View Proof
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">Not uploaded</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($order->status == 'pending')
                                                    <form method="POST" action="{{ route('orders.verify', $order->id) }}" class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="status" value="verified">
                                                        <button type="submit" class="text-blue-600 hover:text-blue-900 mr-3" onclick="return confirm('Mark this order as verified?')">Verify</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('orders.verify', $order->id) }}" class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Reject this order?')">Reject</button>
                                                    </form>
                                                @elseif($order->status == 'verified')
                                                    <form method="POST" action="{{ route('orders.verify', $order->id) }}" class="inline-block">
                                                        @csrf
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Mark order as completed? This usually means the product has been delivered.')">Complete Order</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>

                <!-- Image Modal (Alpine.js) -->
                <div x-show="showModal" 
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" 
                     style="display: none;"
                     @keydown.escape.window="showModal = false">
                    
                    <!-- Modal Background Overlay -->
                    <div class="absolute inset-0" @click="showModal = false"></div>
                    
                    <!-- Modal Content -->
                    <div class="relative bg-white rounded-3xl shadow-2xl overflow-hidden max-w-3xl w-full border border-gray-100 flex flex-col max-h-[90vh]">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="font-bold text-gray-800">Payment Proof</h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 p-1 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="p-6 overflow-y-auto flex items-center justify-center bg-gray-50 min-h-[300px]">
                            <img :src="imageUrl" class="max-w-full max-h-[60vh] object-contain rounded-xl shadow-md border border-gray-200">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
