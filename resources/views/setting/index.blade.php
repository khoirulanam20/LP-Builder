<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings & Payment Methods') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-800 bg-green-100/50 backdrop-blur border border-green-200 p-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Split Layout for Settings -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Payment Methods Card -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 flex flex-col">
                    <div class="p-8 text-gray-900 border-b border-gray-200/50 flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Payment Methods
                        </h3>
                        <p class="text-sm text-gray-600 mb-6">Tambahkan metode pembayaran manual. Pembeli akan mentransfer ke rekening ini.</p>
                        
                        @if($paymentMethods->isEmpty())
                            <p class="text-gray-500 text-sm mb-6 bg-gray-50/80 p-4 rounded-xl border border-gray-100/80">Belum ada metode pembayaran yang ditambahkan.</p>
                        @else
                            <div class="space-y-4 mb-8">
                                @foreach($paymentMethods as $pm)
                                    <div class="bg-white/80 border border-gray-100 rounded-2xl p-5 shadow-sm relative group">
                                        <h4 class="font-bold text-gray-800 text-lg">{{ $pm->name }}</h4>
                                        <div class="text-sm text-gray-600 mt-2 space-y-1">
                                            <div class="flex justify-between"><span class="font-medium text-gray-500">Bank/Provider:</span> <span>{{ $pm->bank_name ?? '-' }}</span></div>
                                            <div class="flex justify-between"><span class="font-medium text-gray-500">Account No:</span> <span class="text-indigo-600 font-mono font-bold">{{ $pm->account_number ?? '-' }}</span></div>
                                        </div>
                                        @if($pm->instructions)
                                        <div class="text-xs text-gray-500 mt-4 pt-3 border-t border-gray-100">
                                            {{ $pm->instructions }}
                                        </div>
                                        @endif
                                        
                                        <form action="{{ route('setting.payment.destroy', $pm->id) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Hapus metode pembayaran ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 bg-red-50 hover:bg-red-100 p-1.5 rounded-lg transition-colors">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <h4 class="text-md font-bold text-gray-800 mb-4 border-t border-gray-200/50 pt-6">Add New Method</h4>
                        <form method="POST" action="{{ route('setting.payment.store') }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="name" value="Method Name (e.g. BCA, OVO)" class="text-xs font-bold text-gray-600" />
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-xl bg-white/50 border-gray-300" required />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="bank_name" value="Bank Name" class="text-xs font-bold text-gray-600" />
                                        <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full rounded-xl bg-white/50 border-gray-300" />
                                    </div>
                                    <div>
                                        <x-input-label for="account_number" value="Account No" class="text-xs font-bold text-gray-600" />
                                        <x-text-input id="account_number" name="account_number" type="text" class="mt-1 block w-full rounded-xl bg-white/50 border-gray-300" />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label for="instructions" value="Payment Instructions (Optional)" class="text-xs font-bold text-gray-600" />
                                    <textarea id="instructions" name="instructions" class="mt-1 block w-full bg-white/50 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm" rows="2"></textarea>
                                </div>
                                <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition transform hover:-translate-y-0.5">Save Method</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Custom Email Message Card -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 flex flex-col">
                    <div class="p-8 text-gray-900 border-b border-gray-200/50 flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                            <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Delivery Email
                        </h3>
                        <p class="text-sm text-gray-600 mb-6">Atur pesan custom yang akan dikirimkan ke email pembeli setelah pembayaran pesanan dikonfirmasi (Status: Berhasil).</p>
                        
                        <div class="bg-pink-50/50 border border-pink-100 rounded-2xl p-5 mb-6 shadow-sm">
                            <h4 class="text-xs font-bold text-pink-800 mb-2 uppercase tracking-wide">Email Preview</h4>
                            <div class="text-sm text-gray-700 space-y-2 font-mono bg-white/80 p-4 rounded-xl border border-pink-100/50">
                                <p>Halo <strong>[Nama Pembeli]</strong>,</p>
                                <p>Terima kasih atas pesanan Anda untuk produk <strong>[Nama Produk]</strong>.</p>
                                <p class="text-pink-600 bg-pink-50 inline-block px-2 py-1 rounded italic">{Pesan Custom Anda akan tampil di sini}</p>
                                <p>Berikut adalah akses produk Anda:<br>
                                <a href="#" class="text-blue-600 underline">Link / Download File</a></p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('setting.email.update') }}" class="mt-auto">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="custom_email_message" value="Custom Email Message" class="text-xs font-bold text-gray-600" />
                                    <textarea id="custom_email_message" name="custom_email_message" class="mt-1 block w-full bg-white/50 border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-xl shadow-sm" rows="6" placeholder="Misal: Terima kasih telah berbelanja! Silakan gabung ke grup Telegram kami di t.me/grup...">{{ old('custom_email_message', $user->custom_email_message) }}</textarea>
                                </div>
                                <button class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition transform hover:-translate-y-0.5">Simpan Pesan Email</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile Settings -->
            <div class="bg-white/60 backdrop-blur-xl shadow-sm sm:rounded-3xl border border-white/50 overflow-hidden">
                <div class="p-6 text-gray-900 flex justify-between items-center px-8">
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg">Account Profile</h3>
                        <p class="text-sm text-gray-600 mt-1">Manage your administrator account profile details and security.</p>
                    </div>
                    <div>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-6 py-2.5 bg-gray-800 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 shadow-md transition transform hover:-translate-y-0.5">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
