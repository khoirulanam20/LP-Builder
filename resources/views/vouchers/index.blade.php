<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Voucher Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-6">
                
                <!-- Left: List -->
                <div class="w-full md:w-2/3">
                    <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                        <div class="p-6 text-gray-900">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Active Vouchers</h3>
                            
                            @if($vouchers->isEmpty())
                                <p class="text-gray-500 text-sm">Tidak ada voucher aktif.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                                <th scope="col" class="relative px-6 py-3">
                                                    <span class="sr-only">Delete</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($vouchers as $voucher)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $voucher->code }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 font-medium">
                                                        @if($voucher->discount_type == 'fixed')
                                                            Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }}
                                                        @else
                                                            {{ $voucher->discount_amount }}%
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $voucher->created_at->format('d M Y') }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <form method="POST" action="{{ route('vouchers.destroy', $voucher->id) }}" onsubmit="return confirm('Hapus voucher ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-900">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Create Form -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Create New</h3>
                            <form method="POST" action="{{ route('vouchers.store') }}">
                                @csrf
                                <div class="mb-4">
                                    <x-input-label for="code" value="Voucher Code" />
                                    <x-text-input id="code" name="code" type="text" class="mt-1 block w-full uppercase" placeholder="e.g. DISKON50" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('code')" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="discount_type" value="Discount Type" />
                                    <select id="discount_type" name="discount_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="fixed">Fixed Amount (Rp)</option>
                                        <option value="percentage">Percentage (%)</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="discount_amount" value="Discount Value" />
                                    <x-text-input id="discount_amount" name="discount_amount" type="number" step="0.01" class="mt-1 block w-full" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('discount_amount')" />
                                </div>
                                <x-primary-button class="w-full justify-center">{{ __('Create Voucher') }}</x-primary-button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
