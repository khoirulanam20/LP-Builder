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
                <!-- Meta Pixel Settings Card -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 flex flex-col">
                    <div class="p-8 text-gray-900 border-b border-gray-200/50 flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Meta Pixel Configuration
                        </h3>
                        <p class="text-sm text-gray-600 mb-6">Hubungkan Meta Pixel Anda untuk melacak kunjungan dan konversi penjualan secara otomatis.</p>
                        
                        <div class="bg-indigo-50/50 border border-indigo-100/50 rounded-2xl p-6 mb-8 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div class="bg-indigo-600 p-2 rounded-lg text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-black text-indigo-600 mb-1 uppercase tracking-widest">Cara Mendapatkan ID</h4>
                                    <p class="text-[11px] text-indigo-900 leading-relaxed font-medium">Buka Meta Events Manager, pilih Data Source Anda, dan salin <b>Pixel ID</b> (angka) pada tab Settings.</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('setting.meta-pixel.update') }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="meta_pixel_id" value="Meta Pixel ID" class="text-xs font-bold text-gray-600" />
                                    <x-text-input id="meta_pixel_id" name="meta_pixel_id" type="text" class="mt-1 block w-full rounded-xl bg-white/50 border-gray-300" placeholder="Contoh: 123456789012345" value="{{ old('meta_pixel_id', $user->meta_pixel_id) }}" />
                                    @error('meta_pixel_id')
                                        <p class="mt-1 text-xs text-red-500 font-bold italic">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                                    Simpan Konfigurasi Pixel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Custom Email Message Card -->
                <div class="bg-white/60 backdrop-blur-xl shadow-xl rounded-3xl border border-white/50 flex flex-col" x-data="{ customMsg: {{ json_encode(old('custom_email_message', $user->custom_email_message) ?? '') }} }">
                    <div class="p-8 text-gray-900 border-b border-gray-200/50 flex-1">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-2">
                            <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Delivery Email
                        </h3>
                        <p class="text-sm text-gray-600 mb-6">Atur pesan custom yang akan dikirimkan ke email pembeli setelah pembayaran pesanan dikonfirmasi (Status: Berhasil).</p>
                        
                        <div class="bg-gray-100/50 border border-gray-200 rounded-2xl p-5 mb-6 shadow-sm">
                            <h4 class="text-[10px] font-black text-gray-400 mb-3 uppercase tracking-widest">EMAIL PREVIEW</h4>
                            <div class="text-xs text-gray-700 space-y-3 bg-white p-6 rounded-2xl shadow-inner border border-gray-100">
                                <p class="font-bold text-sm text-gray-900 overflow-hidden"># Halo [Nama Pembeli],</p>
                                <p>Terima kasih telah melakukan pembelian produk <strong>[Nama Produk]</strong>.</p>
                                
                                <template x-if="customMsg && customMsg.trim() !== ''">
                                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-xl text-gray-700 italic text-[11px] whitespace-pre-wrap shadow-sm" x-text="customMsg"></div>
                                </template>
                                <template x-if="!customMsg || customMsg.trim() === ''">
                                    <div class="bg-pink-50 border border-pink-100 p-4 rounded-xl text-pink-500 italic text-[11px] font-semibold flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        Pesan Custom Anda akan tampil di sini
                                    </div>
                                </template>
                                
                                <p class="pt-2 text-gray-600">Berikut adalah akses ke produk Anda:</p>
                                <div class="bg-indigo-600 text-white text-center py-2.5 rounded-lg font-bold text-[11px] shadow-sm">
                                    Akses / Download Produk
                                </div>
                                <p class="text-[10px] text-gray-400 mt-4 border-t border-gray-50 pt-3">
                                    Terima kasih,<br>
                                    <strong>{{ config('app.name') }}</strong>
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('setting.email.update') }}" class="mt-auto">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="custom_email_message" value="Custom Email Message" class="text-xs font-bold text-gray-600" />
                                    <textarea id="custom_email_message" name="custom_email_message" x-model="customMsg" class="mt-1 block w-full bg-white/50 border-gray-300 focus:border-pink-500 focus:ring-pink-500 rounded-xl shadow-sm" rows="6" placeholder="Misal: Terima kasih telah berbelanja! Silakan gabung ke grup Telegram kami di t.me/grup...">{{ old('custom_email_message', $user->custom_email_message) }}</textarea>
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
