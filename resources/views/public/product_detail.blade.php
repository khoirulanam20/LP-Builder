<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - {{ $landingPage->title }}</title>
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

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(52, 101, 109, 0.1);
        }
        
        .animation-slide-up {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn { 
            from { transform: translateY(10%); opacity: 0; } 
            to { transform: translateY(0); opacity: 1; } 
        }
    </style>
</head>
<body class="text-slate-800 font-sans">
    <div class="max-w-xl mx-auto min-h-screen bg-white relative pb-32 animation-slide-up shadow-2xl">
        
        <a href="{{ route('public.show', $landingPage->slug) }}" class="absolute top-6 left-6 z-[110] w-12 h-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-primary border border-slate-100 hover:scale-105 active:scale-90 transition">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Hero Image -->
        <div class="w-full h-80 bg-slate-200 overflow-hidden rounded-b-[3rem] shadow-lg relative flex items-center justify-center">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
            @else
                <i class="fas fa-box text-6xl text-slate-400"></i>
            @endif
        </div>

        <div class="p-8">
            <div>
                <h2 class="text-3xl font-black text-dark leading-tight">{{ $product->name }}</h2>
                <div class="mt-2 text-2xl font-bold text-primary">
                    @if($product->sale_price && $product->sale_price > 0)
                        <span class="text-slate-400 line-through text-lg mr-2 font-medium">IDR {{ number_format($product->price, 0, ',', '.') }}</span>
                        IDR {{ number_format($product->sale_price, 0, ',', '.') }}
                    @else
                        IDR {{ number_format($product->price, 0, ',', '.') }}
                    @endif
                </div>
            </div>

            <div class="mt-8">
                <div class="glass-card p-6 rounded-3xl">
                    <h4 class="font-bold text-primary mb-3 uppercase tracking-wider text-xs italic"><i class="fas fa-info-circle mr-2"></i>Deskripsi Produk</h4>
                    <div class="text-sm leading-relaxed text-slate-600 prose prose-sm max-w-none">
                        {!! $product->description ?? 'Tidak ada deskripsi rinci untuk produk ini.' !!}
                    </div>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div style="margin-top:1.5rem;">
                <div style="background:#fff; border:1px solid #e2e8f0; padding:1.25rem 1.5rem; border-radius:1.5rem; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                        <div>
                            <p style="font-weight:800; font-size:0.7rem; text-transform:uppercase; letter-spacing:0.08em; color:#121212; font-style:italic; margin:0; display:flex; align-items:center; gap:6px;">
                                <i class="fas fa-cubes" style="color:{{ $themeColor }};"></i>Jumlah Pembelian
                            </p>
                            <p id="qty-total-label" style="font-size:0.72rem; color:#94a3b8; margin:4px 0 0 0;">
                                Subtotal: IDR {{ number_format($product->sale_price && $product->sale_price > 0 ? $product->sale_price : $product->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div style="display:flex; align-items:center; gap:0.5rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:1rem; padding:6px;">
                            <button id="qty-minus" onclick="changeQty(-1)" style="width:2.25rem; height:2.25rem; border-radius:0.625rem; background:#fff; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:900; color:#475569; cursor:pointer; box-shadow:0 1px 3px rgba(0,0,0,0.08); flex-shrink:0;">
                                &minus;
                            </button>
                            <span id="qty-display" style="min-width:2rem; text-align:center; font-size:1.25rem; font-weight:900; color:#121212; user-select:none; display:inline-block;">1</span>
                            <button id="qty-plus" onclick="changeQty(1)" style="width:2.25rem; height:2.25rem; border-radius:0.625rem; background:{{ $themeColor }}; border:none; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:900; color:#fff; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,0.15); flex-shrink:0;">
                                +
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                var qtyVal = 1;
                var basePrice = {{ $product->sale_price && $product->sale_price > 0 ? $product->sale_price : $product->price }};
                var checkoutBase = '{{ route('public.checkout', ['slug' => $landingPage->slug, 'product_id' => $product->id]) }}';

                function formatRupiah(num) {
                    return 'IDR ' + num.toLocaleString('id-ID');
                }

                function changeQty(delta) {
                    qtyVal = Math.max(1, qtyVal + delta);
                    document.getElementById('qty-display').textContent = qtyVal;
                    document.getElementById('qty-total-label').textContent = 'Subtotal: ' + formatRupiah(basePrice * qtyVal);
                    // update the buy button href
                    var btn = document.getElementById('btn-beli');
                    btn.href = checkoutBase + '&qty=' + qtyVal;
                }
            </script>

            <!-- Add-ons Opsional -->
            @if($product->addOns && count($product->addOns) > 0)
            <div class="mt-8">
                <h4 class="font-black text-dark text-sm uppercase tracking-widest mb-4 italic underline decoration-secondary decoration-4 underline-offset-4 pl-2">Add-ons Opsional</h4>
                <div class="space-y-3">
                    @foreach($product->addOns as $addon)
                        <div class="bg-accent p-4 rounded-xl flex justify-between items-center border border-slate-200 shadow-sm">
                            <span class="text-sm font-semibold text-slate-700">{{ $addon->name }}</span>
                            <span class="text-xs font-bold text-white bg-primary px-3 py-1.5 rounded-lg shadow">+IDR {{ number_format($addon->price, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="fixed bottom-8 left-0 w-full px-6 z-[110] flex justify-center">
            <div class="w-full max-w-xl">
                <a id="btn-beli" href="{{ route('public.checkout', ['slug' => $landingPage->slug, 'product_id' => $product->id]) }}&qty=1" class="btn-shine bg-primary text-white flex items-center justify-center w-full py-5 rounded-2xl font-black text-xl text-center shadow-[0_20px_40px_rgba(52,101,109,0.3)] animate-cta-btn">
                    <i class="fas fa-shopping-cart mr-2"></i> BELI SEKARANG
                </a>
            </div>
        </div>
    </div>
</body>
</html>
