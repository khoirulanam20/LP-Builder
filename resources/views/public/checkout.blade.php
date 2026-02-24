<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ $landingPage->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @php
        $themeColor = $landingPage->appearance->theme_color ?? '#34656D';
        $pulseColor = $themeColor . '66';
        $pulseColorZero = $themeColor . '00';
    @endphp

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $themeColor }}',
                        secondary: '#FAEAB1',
                        accent: '#FAF8F1',
                        dark: '#121212'
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #FAF8F1; }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(52, 101, 109, 0.1);
        }
        
        @keyframes custom-pulse {
            0% { box-shadow: 0 0 0 0 {{ $pulseColor }}; }
            70% { box-shadow: 0 0 0 15px {{ $pulseColorZero }}; }
            100% { box-shadow: 0 0 0 0 {{ $pulseColorZero }}; }
        }
        .animate-cta-btn { animation: custom-pulse 2s infinite; }
        
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -60%;
            width: 20%;
            height: 200%;
            background: rgba(255, 255, 255, 0.4);
            transform: rotate(30deg);
            animation: shine-effect 3s infinite;
        }
        @keyframes shine-effect {
            0% { left: -60%; }
            20% { left: 120%; }
            100% { left: 120%; }
        }
    </style>
</head>
<body class="text-slate-800 font-sans min-h-screen relative pb-10">

    <div class="max-w-xl mx-auto px-4 py-8">
        <a href="{{ route('public.show', $landingPage->slug) }}" class="inline-flex items-center text-primary bg-white/60 px-5 py-2.5 rounded-2xl shadow-sm backdrop-blur-sm border border-slate-200 hover:scale-105 active:scale-90 transition font-bold text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="max-w-xl mx-auto min-h-screen bg-white shadow-2xl border border-slate-100 rounded-[3rem] overflow-hidden relative pb-10">
        
        <div class="bg-dark text-white p-8 pt-10 text-center rounded-b-[2rem] shadow-lg relative">
            <h1 class="text-2xl font-black tracking-tight"><i class="fas fa-lock text-secondary mr-2"></i>Secure Checkout</h1>
            <p class="text-xs text-slate-300 mt-2">Selesaikan pembayaran Anda dengan mudah dan aman.</p>
        </div>

        @if(session('order_id'))
            <script>
                window.location.href = "{{ route('public.success', $landingPage->slug) }}";
            </script>
        @endif

        <div class="p-8">
            @if($errors->any())
                <div class="bg-red-50 text-red-600 border border-red-200 p-4 rounded-2xl mb-6 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err)
                            <li class="font-medium">{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Order Summary -->
            <div class="glass-card rounded-3xl p-6 mb-8 shadow-sm">
                <h2 class="font-bold text-primary mb-4 uppercase tracking-wider text-xs italic"><i class="fas fa-receipt mr-2"></i>Order Summary</h2>
                
                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-accent rounded-xl flex items-center justify-center text-primary text-xl shadow-inner mr-3">
                            <i class="fas fa-box"></i>
                        </div>
                        <span class="text-sm font-bold text-dark">{{ $product->name }}</span>
                    </div>
                    <span class="font-bold text-dark text-sm">IDR {{ number_format($price, 0, ',', '.') }}</span>
                </div>

                <!-- Voucher Section -->
                <div class="mb-4">
                    @if(session('success'))
                        <div class="text-green-600 text-xs font-bold mb-2"><i class="fas fa-check-circle mr-1"></i>{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="text-red-500 text-xs font-bold mb-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ session('error') }}</div>
                    @endif
                    
                    <form action="{{ route('public.applyVoucher', $landingPage->slug) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="code" placeholder="Kode Diskon / Voucher" value="{{ $voucher ? $voucher->code : '' }}" class="flex-1 w-full bg-slate-50 border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary text-sm px-4 py-3 font-medium outline-none transition" {{ $voucher ? 'readonly' : '' }}>
                        <button type="submit" class="{{ $voucher ? 'bg-slate-300 text-slate-500' : 'bg-dark text-secondary hover:bg-black' }} px-5 py-3 rounded-xl text-xs font-black tracking-widest uppercase transition shadow-sm" {{ $voucher ? 'disabled' : '' }}>
                            {{ $voucher ? 'TERAPKAN' : 'APPLY' }}
                        </button>
                    </form>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <div class="flex justify-between items-center mb-2 text-sm text-slate-500 font-medium">
                        <span>Subtotal</span>
                        <span>IDR {{ number_format($price, 0, ',', '.') }}</span>
                    </div>
                    @if($discountAmount > 0)
                        <div class="flex justify-between items-center mb-3 text-sm text-green-600 font-bold bg-green-50 p-2 rounded-lg">
                            <span>Diskon ({{ $voucher->code }})</span>
                            <span>- IDR {{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center mt-3 pt-4 border-t-2 border-dashed border-slate-200">
                        <span class="text-dark font-black tracking-wide pl-1">Total Transfer</span>
                        <span class="text-primary font-black text-xl">IDR {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <form action="{{ route('public.checkout.process', $landingPage->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <h2 class="font-bold text-primary mb-4 uppercase tracking-wider text-xs italic"><i class="fas fa-user mr-2"></i>Data Pembeli</h2>
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2 ml-1">Nama Lengkap</label>
                        <input type="text" name="customer_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary px-4 py-3 text-sm font-medium outline-none transition" placeholder="Masukkan nama Anda">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-2 ml-1">Email Aktif</label>
                        <input type="email" name="customer_email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary px-4 py-3 text-sm font-medium outline-none transition" placeholder="Produk akan dikirim ke email ini">
                        <p class="text-[10px] text-slate-400 mt-1.5 ml-1 italic">*Pastikan email aktif dan benar.</p>
                    </div>
                </div>

                <h2 class="font-bold text-primary mb-4 border-t border-slate-100 pt-8 uppercase tracking-wider text-xs italic"><i class="fas fa-wallet mr-2"></i>Metode Pembayaran</h2>
                <p class="text-xs text-slate-500 mb-4 bg-accent p-3.5 rounded-xl border border-secondary shadow-sm leading-relaxed">
                    Silakan transfer tepat <strong class="text-primary text-sm shadow-sm bg-white px-2 py-0.5 rounded ml-1">IDR {{ number_format($totalAmount, 0, ',', '.') }}</strong> ke salah satu rekening di bawah ini, lalu unggah bukti transfer yang valid.
                </p>
                
                @if($paymentMethods->isEmpty())
                    <div class="p-4 bg-red-50 text-red-600 text-sm font-bold rounded-xl mb-6 flex items-center shadow-sm border border-red-100">
                        <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
                        Penjual belum mengatur metode pembayaran tujuan. Silakan hubungi penjual.
                    </div>
                @else
                    <div class="space-y-3 mb-8">
                        @foreach($paymentMethods as $pm)
                            <div class="border-2 border-slate-100 hover:border-primary rounded-2xl p-4 bg-white transition cursor-pointer group shadow-sm hover:shadow-md">
                                <div class="font-black text-sm text-dark group-hover:text-primary transition">{{ $pm->name }} ({{ $pm->bank_name }})</div>
                                <div class="text-xl font-mono tracking-widest text-primary mt-2 select-all bg-accent inline-block px-3 py-1 rounded-lg border border-primary/20">{{ $pm->account_number }}</div>
                                @if($pm->instructions)
                                    <div class="text-xs text-slate-500 mt-3 flex items-start leading-relaxed"><i class="fas fa-info-circle mt-0.5 mr-2 text-secondary"></i> {{ $pm->instructions }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mb-10">
                    <label class="block text-xs font-bold text-slate-600 mb-2 ml-1">Upload Bukti Transfer</label>
                    <div class="border-2 border-dashed border-primary/30 rounded-2xl p-8 text-center bg-accent/50 hover:bg-accent transition group cursor-pointer relative overflow-hidden">
                        <i class="fas fa-cloud-upload-alt text-4xl text-primary/40 group-hover:text-primary transition mb-3"></i>
                        <input type="file" name="payment_proof" accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="text-sm font-bold text-primary bg-white border border-primary/20 px-4 py-2 rounded-xl inline-block shadow-sm group-hover:shadow-md transition">Pilih File Bukti</div>
                        <p class="text-[10px] text-slate-400 mt-3 font-medium leading-relaxed">Format: JPG, PNG maks 2MB.<br>Pastikan nominal terlihat jelas agar otomatis diproses.</p>
                    </div>
                </div>

                <button type="submit" class="w-full btn-shine bg-primary text-white font-black text-lg py-5 rounded-2xl shadow-[0_15px_30px_rgba(52,101,109,0.3)] animate-cta-btn flex justify-center items-center">
                    <i class="fas fa-check-circle mr-2"></i> KONFIRMASI PEMBAYARAN
                </button>
            </form>

            <div class="mt-8 text-center text-[10px] font-medium text-slate-400">
                &copy; {{ date('Y') }} All Rights Reserved.<br>Secured by LP Builder.
            </div>
        </div>
    </div>
</body>
</html>
