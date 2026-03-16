<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ Str::limit(strip_tags($product->description ?? $landingPage->description ?? $landingPage->title), 150) }}">
    <meta name="theme-color" content="{{ $landingPage->appearance->theme_color ?? '#34656D' }}">
    <title>{{ $product->name }} - {{ $landingPage->title }}</title>
    
    <!-- Preconnect & DNS Prefetch -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"></noscript>
    
    @php
        $themeColor = $landingPage->appearance->theme_color ?? '#34656D';
        $pulseColor = $themeColor . '66';
        $pulseColorZero = $themeColor . '00';
        
        $hex = ltrim($themeColor, '#');
        $r = hexdec(strlen($hex) == 3 ? str_repeat(substr($hex, 0, 1), 2) : substr($hex, 0, 2));
        $g = hexdec(strlen($hex) == 3 ? str_repeat(substr($hex, 1, 1), 2) : substr($hex, 2, 2));
        $b = hexdec(strlen($hex) == 3 ? str_repeat(substr($hex, 2, 1), 2) : substr($hex, 4, 2));
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        $primaryTextColor = $luminance > 0.6 ? '#121212' : '#ffffff';
    @endphp

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $themeColor }}',
                        'primary-text': '{{ $primaryTextColor }}',
                        secondary: '#FAEAB1',
                        accent: '#FAF8F1',
                        dark: '#121212'
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F8FAFC; }
        .product-card { transition: all 0.2s ease; cursor: pointer; }
        
        @keyframes soft-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.95; box-shadow: 0 4px 20px -2px {{ $pulseColor }}; }
        }
        .animate-cta-btn { animation: soft-pulse 2s infinite; }

        .glass-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
        }
        
        .animation-slide-up {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn { 
            from { transform: translateY(10%); opacity: 0; } 
            to { transform: translateY(0); opacity: 1; } 
        }
    </style>
    @include('public.partials.meta_pixel')
</head>
<body class="text-slate-800 font-sans">
    <div class="max-w-xl mx-auto min-h-screen bg-white relative pb-32 animation-slide-up shadow-sm">
        
        <a href="{{ route('public.show', $landingPage->slug) }}" class="absolute top-6 left-6 z-[110] w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center text-gray-700 border border-gray-100 hover:scale-105 active:scale-95 transition">
            <i class="fas fa-arrow-left"></i>
        </a>

        <!-- Hero Image -->
        <div class="w-full h-80 bg-gray-100 overflow-hidden relative flex items-center justify-center border-b border-gray-100">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" fetchpriority="high" class="w-full h-full object-cover">
            @else
                <i class="fas fa-box text-6xl text-slate-400"></i>
            @endif
        </div>

        <div class="p-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 leading-tight tracking-tight">{{ $product->name }}</h2>
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
                <div class="glass-card p-6 rounded-2xl">
                    <h4 class="font-bold text-gray-900 mb-3 text-sm">Deskripsi Produk</h4>
                    <div class="text-sm leading-relaxed text-gray-600 prose prose-sm max-w-none">
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
                            <button id="qty-plus" onclick="changeQty(1)" style="width:2.25rem; height:2.25rem; border-radius:0.625rem; background:{{ $themeColor }}; border:none; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:900; color:{{ $primaryTextColor }}; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,0.15); flex-shrink:0;">
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
                <h4 class="font-bold text-gray-900 text-sm mb-4">Add-ons Opsional</h4>
                <div class="space-y-3">
                    @foreach($product->addOns as $addon)
                        <div class="bg-accent p-4 rounded-xl flex justify-between items-center border border-slate-200 shadow-sm">
                            <span class="text-sm font-semibold text-slate-700">{{ $addon->name }}</span>
                            <span class="text-xs font-bold text-primary-text bg-primary px-3 py-1.5 rounded-lg shadow">+IDR {{ number_format($addon->price, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="fixed bottom-8 left-0 w-full px-6 z-[110] flex justify-center">
            <div class="w-full max-w-xl">
                <a id="btn-beli" href="{{ route('public.checkout', ['slug' => $landingPage->slug, 'product_id' => $product->id]) }}&qty=1" class="bg-primary text-primary-text flex items-center justify-center w-full py-4 rounded-xl font-bold text-lg text-center shadow-md animate-cta-btn transition hover:bg-opacity-90">
                    <i class="fas fa-shopping-cart mr-2"></i> BELI SEKARANG
                </a>
            </div>
        </div>
    </div>
</body>
</html>
