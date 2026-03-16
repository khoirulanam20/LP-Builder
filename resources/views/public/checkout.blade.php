<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ $landingPage->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Midtrans Snap -->
    <script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $themeColor     = $landingPage->appearance->theme_color ?? '#34656D';
        $pulseColor     = $themeColor . '66';
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
        .glass-card { background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border: 1px solid rgba(52,101,109,0.1); }
        @keyframes custom-pulse {
            0%   { box-shadow: 0 0 0 0 {{ $pulseColor }}; }
            70%  { box-shadow: 0 0 0 15px {{ $pulseColorZero }}; }
            100% { box-shadow: 0 0 0 0 {{ $pulseColorZero }}; }
        }
        .animate-cta-btn { animation: custom-pulse 2s infinite; }
        .btn-shine { position: relative; overflow: hidden; }
        .btn-shine::after {
            content: "";
            position: absolute; top: -50%; left: -60%;
            width: 20%; height: 200%;
            background: rgba(255,255,255,0.4);
            transform: rotate(30deg);
            animation: shine-effect 3s infinite;
        }
        @keyframes shine-effect {
            0%   { left: -60%; }
            20%  { left: 120%; }
            100% { left: 120%; }
        }
        #pay-btn:disabled { opacity: 0.6; cursor: not-allowed; }
    </style>
</head>
<body class="text-slate-800 font-sans min-h-screen relative pb-10">

    <div class="max-w-xl mx-auto px-4 py-8">
        <a href="{{ route('public.show', $landingPage->slug) }}" class="inline-flex items-center text-primary bg-white/60 px-5 py-2.5 rounded-2xl shadow-sm backdrop-blur-sm border border-slate-200 hover:scale-105 active:scale-90 transition font-bold text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="max-w-xl mx-auto min-h-screen bg-white shadow-2xl border border-slate-100 rounded-[3rem] overflow-hidden relative pb-10">

        <div class="bg-dark text-white p-8 pt-10 text-center rounded-b-[2rem] shadow-lg">
            <h1 class="text-2xl font-black tracking-tight"><i class="fas fa-lock text-secondary mr-2"></i>Secure Checkout</h1>
            <p class="text-xs text-slate-300 mt-2">Selesaikan pembayaran Anda dengan mudah & aman via Midtrans.</p>
        </div>

        <div class="p-8">

            <!-- Order Summary -->
            <div class="glass-card rounded-3xl p-6 mb-8 shadow-sm">
                <h2 class="font-bold text-primary mb-4 uppercase tracking-wider text-xs italic"><i class="fas fa-receipt mr-2"></i>Order Summary</h2>

                <div class="flex items-center justify-between mb-4 border-b border-slate-100 pb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-accent rounded-xl flex items-center justify-center text-primary text-xl shadow-inner mr-3">
                            <i class="fas fa-box"></i>
                        </div>
                        <span class="text-sm font-bold text-dark">{{ $product->name }} <span class="text-xs text-slate-400 font-medium ml-1">x {{ $qty }}</span></span>
                    </div>
                    <span class="font-bold text-dark text-sm">IDR {{ number_format($price, 0, ',', '.') }}</span>
                </div>

                <!-- Voucher -->
                <div class="mb-4">
                    @if(session('success'))
                        <div class="text-green-600 text-xs font-bold mb-2"><i class="fas fa-check-circle mr-1"></i>{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="text-red-500 text-xs font-bold mb-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('public.applyVoucher', $landingPage->slug) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="code" placeholder="Kode Diskon / Voucher" value="{{ $voucher ? $voucher->code : '' }}" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary text-sm px-4 py-3 font-medium outline-none transition" {{ $voucher ? 'readonly' : '' }}>
                        <button type="submit" class="{{ $voucher ? 'bg-slate-300 text-slate-500' : 'bg-dark text-secondary hover:bg-black' }} px-5 py-3 rounded-xl text-xs font-black tracking-widest uppercase transition shadow-sm" {{ $voucher ? 'disabled' : '' }}>
                            {{ $voucher ? 'TERAPKAN' : 'APPLY' }}
                        </button>
                    </form>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <div class="flex justify-between items-center mb-2 text-sm text-slate-500 font-medium">
                        <span>Subtotal</span><span>IDR {{ number_format($price, 0, ',', '.') }}</span>
                    </div>
                    @if($discountAmount > 0)
                        <div class="flex justify-between items-center mb-3 text-sm text-green-600 font-bold bg-green-50 p-2 rounded-lg">
                            <span>Diskon ({{ $voucher->code }})</span>
                            <span>- IDR {{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($serviceFee > 0)
                        <div class="flex justify-between items-center mb-3 text-sm text-slate-500 font-medium italic">
                            <span>Biaya Layanan</span>
                            <span>+ IDR {{ number_format($serviceFee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center mt-3 pt-4 border-t-2 border-dashed border-slate-200">
                        <span class="text-dark font-black tracking-wide pl-1">Total Pembayaran</span>
                        <span class="text-primary font-black text-xl">IDR {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Buyer Info -->
            <h2 class="font-bold text-primary mb-4 uppercase tracking-wider text-xs italic"><i class="fas fa-user mr-2"></i>Data Pembeli</h2>
            <div class="space-y-4 mb-8">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 ml-1">Nama Lengkap</label>
                    <input id="field-name" type="text" required class="w-full bg-slate-50 border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary px-4 py-3 text-sm font-medium outline-none transition" placeholder="Masukkan nama Anda">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2 ml-1">Email Aktif</label>
                    <input id="field-email" type="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary px-4 py-3 text-sm font-medium outline-none transition" placeholder="Produk dikirim ke email ini">
                    <p class="text-[10px] text-slate-400 mt-1.5 ml-1 italic">*Pastikan email aktif dan benar.</p>
                </div>
            </div>

            <!-- Midtrans notice -->
            <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-8">
                <p class="text-xs text-blue-700 leading-relaxed">Pembayaran diproses secara aman oleh <strong>Midtrans</strong>. Tersedia transfer bank, QRIS, e-wallet (GoPay, OVO, Dana), kartu kredit, dan lainnya.</p>
            </div>

            <!-- Error -->
            <div id="checkout-error" class="hidden bg-red-50 text-red-600 border border-red-200 p-4 rounded-2xl mb-4 text-sm font-medium"></div>

            @if(!$clientKey)
                <div class="bg-amber-50 border border-amber-200 text-amber-700 p-4 rounded-2xl text-sm font-medium mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Midtrans belum dikonfigurasi. Silakan hubungi administrator.
                </div>
            @endif

            <button id="pay-btn"
                onclick="startPayment()"
                class="w-full btn-shine bg-primary text-white font-black text-lg py-5 rounded-2xl shadow-[0_15px_30px_rgba(52,101,109,0.3)] animate-cta-btn flex justify-center items-center gap-2"
                {{ !$clientKey ? 'disabled' : '' }}>
                <i class="fas fa-shield-alt"></i> BAYAR SEKARANG
            </button>

            <div class="mt-8 text-center text-[10px] font-medium text-slate-400">
                &copy; {{ date('Y') }} All Rights Reserved. Secured by LP Builder &amp; Midtrans.
            </div>
        </div>
    </div>

    <script>
        const productId  = @json($product->id);
        const qty        = @json($qty);
        const orderRef   = @json($orderRef);
        const processUrl = @json(route('public.checkout.process', $landingPage->slug));
        const successUrl = @json(route('public.success', $landingPage->slug));
        const csrfToken  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function startPayment() {
            const name  = document.getElementById('field-name').value.trim();
            const email = document.getElementById('field-email').value.trim();
            const errEl = document.getElementById('checkout-error');

            if (!name || !email) {
                errEl.textContent = 'Mohon isi nama dan email terlebih dahulu.';
                errEl.classList.remove('hidden');
                return;
            }
            errEl.classList.add('hidden');

            const btn = document.getElementById('pay-btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

            // 1. Request Snap Token via AJAX
            fetch(processUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    customer_name:  name,
                    customer_email: email,
                    product_id:     productId,
                    qty:            qty,
                    order_ref:      orderRef
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'ok' && data.snap_token) {
                    // 2. Open Snap Popup
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = successUrl + "?order_id=" + orderRef;
                        },
                        onPending: function(result) {
                            window.location.href = successUrl + "?order_id=" + orderRef;
                        },
                        onError: function(result) {
                            errEl.textContent = 'Pembayaran gagal. Silakan coba lagi.';
                            errEl.classList.remove('hidden');
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-shield-alt"></i> BAYAR SEKARANG';
                        },
                        onClose: function() {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-shield-alt"></i> BAYAR SEKARANG';
                        }
                    });
                } else {
                    throw new Error(data.message || 'Gagal membuat transaksi');
                }
            })
            .catch(error => {
                errEl.textContent = error.message;
                errEl.classList.remove('hidden');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-shield-alt"></i> BAYAR SEKARANG';
            });
        }
    </script>
</body>
</html>
