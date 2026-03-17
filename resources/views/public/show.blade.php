<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ Str::limit(strip_tags($landingPage->description ?? $landingPage->title), 150) }}">
    <meta name="theme-color" content="{{ $landingPage->appearance->theme_color ?? '#34656D' }}">
    <title>{{ $landingPage->title }}</title>
    
    <!-- Preconnect & DNS Prefetch -->
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"></noscript>
    
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'" />
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"></noscript>
    
    @php
        $themeColor = $landingPage->appearance->theme_color ?? '#34656D';
        // Add dynamic secondary/accent colors based on what fits - for now we hardcode complementary ones based on user reference
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
                        secondary: '#F1F5F9',
                        accent: '#FFFFFF',
                        dark: '#0F172A'
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F8FAFC; }
        .product-card { transition: all 0.2s ease; cursor: pointer; }
        .product-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025); border-color: {{ $themeColor }}33; }
        
        .animate-cta-btn { animation: soft-pulse 2s infinite; }
        @keyframes soft-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.95; box-shadow: 0 4px 20px -2px {{ $pulseColor }}; }
        }

        .testimonialSwiper .swiper-wrapper { transition-timing-function: linear !important; }

        .detail-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(248, 250, 252, 0.95);
            backdrop-filter: blur(8px);
            z-index: 100;
            overflow-y: auto;
        }
        .detail-active { display: block; animation: fadeIn 0.2s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .glass-card {
            background: #ffffff;
            border: 1px solid #E2E8F0;
        }
    </style>
    @include('public.partials.meta_pixel')
</head>
<body class="text-slate-800 font-sans">

    <main id="mainPage" class="max-w-xl mx-auto min-h-screen shadow-2xl bg-white relative">
        @if(session('review_success'))
            <div id="reviewAlert" class="absolute top-4 left-1/2 transform -translate-x-1/2 z-50 w-11/12 max-w-sm animate-bounce">
                <div class="bg-green-500 text-white px-6 py-3 rounded-2xl shadow-xl flex items-center font-bold text-sm">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    {{ session('review_success') }}
                </div>
            </div>
            <script>
                setTimeout(() => {
                    const alert = document.getElementById('reviewAlert');
                    if(alert) alert.style.display = 'none';
                }, 4000);
            </script>
        @endif
        
        <header class="pt-12 pb-10 px-6 text-center bg-white border-b border-gray-100 relative overflow-hidden shadow-sm">
            @if($landingPage->image_path)
                <div class="absolute inset-0 opacity-20 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $landingPage->image_path) }}')"></div>
            @endif

            <div class="relative z-10">
                <div class="mb-6 relative inline-block">
                    @if($landingPage->appearance && $landingPage->appearance->logo_path)
                        <img src="{{ asset('storage/' . $landingPage->appearance->logo_path) }}" alt="Logo" fetchpriority="high" class="w-24 h-24 object-cover rounded-full border border-gray-100 mx-auto shadow-sm bg-white">
                    @else
                        <!-- Fallback Logo if not provided -->
                        <div class="w-24 h-24 rounded-full border border-gray-100 mx-auto shadow-sm bg-primary flex items-center justify-center text-primary-text text-4xl font-bold">
                            {{ substr($landingPage->title, 0, 1) }}
                        </div>
                    @endif
                    <div class="absolute bottom-0 right-0 bg-green-500 w-5 h-5 rounded-full border-2 border-white"></div>
                </div>
                
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900">{{ $landingPage->title }}</h1>
                
                @if($landingPage->description)
                    <p class="mt-3 text-gray-500 font-medium px-4 text-sm">{{ $landingPage->description }}</p>
                @endif
                
                @if($landingPage->appearance && $landingPage->appearance->about_text)
                    <p class="mt-2 text-gray-400 px-4 text-xs">{{ $landingPage->appearance->about_text }}</p>
                @endif
                
                @if($landingPage->appearance && $landingPage->appearance->social_links)
                    <div class="flex justify-center flex-wrap gap-5 mt-6">
                        @foreach($landingPage->appearance->social_links as $platform => $url)
                            @php
                                $icon = 'fa-globe';
                                if(stripos($platform, 'instagram') !== false) $icon = 'fa-instagram';
                                elseif(stripos($platform, 'tiktok') !== false) $icon = 'fa-tiktok';
                                elseif(stripos($platform, 'youtube') !== false) $icon = 'fa-youtube';
                                elseif(stripos($platform, 'whatsapp') !== false) $icon = 'fa-whatsapp';
                                elseif(stripos($platform, 'facebook') !== false) $icon = 'fa-facebook';
                                elseif(stripos($platform, 'twitter') !== false) $icon = 'fa-twitter';
                            @endphp
                            <a href="{{ $url }}" target="_blank" class="text-gray-400 hover:text-primary transition text-xl">
                                <i class="fab {{ $icon }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </header>

        <section class="px-4 py-8 space-y-4">
            <h2 class="font-bold text-gray-900 text-lg mb-6">Katalog Produk</h2>

            @forelse($landingPage->products as $product)
                <div onclick="showDetail({{ $product->id }})" class="product-card group relative bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex items-center p-3 hover:border-primary">
                    <div class="w-20 h-20 bg-accent rounded-xl flex-shrink-0 flex items-center justify-center text-primary text-2xl overflow-hidden shadow-inner">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" loading="lazy" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-layer-group"></i>
                        @endif
                    </div>
                    
                    <div class="ml-4 flex-grow">
                        <h3 class="font-bold text-dark text-sm leading-tight">{{ $product->name }}</h3>
                        <div class="flex items-center mt-1">
                            @if($product->sale_price && $product->sale_price > 0)
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded mr-2 italic">IDR {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                <span class="text-slate-400 text-[10px] line-through italic">IDR {{ number_format($product->price, 0, ',', '.') }}</span>
                            @else
                                <span class="text-[10px] text-slate-500 mt-1 font-bold">IDR {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-2 text-gray-300 p-2 rounded-full group-hover:text-primary transition">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 text-sm py-8">Belum ada produk saat ini.</p>
            @endforelse
        </section>

        <!-- Dynamic Testimonials -->
        @if($landingPage->reviews && $landingPage->reviews->where('is_approved', true)->count() > 0)
            <section class="py-10 bg-accent border-y border-slate-200">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Real Reviews dari Pembeli</p>
                <div class="swiper testimonialSwiper">
                    <div class="swiper-wrapper">
                        @foreach($landingPage->reviews->where('is_approved', true) as $review)
                        <div class="swiper-slide !w-72">
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm h-full flex flex-col justify-between">
                                <div>
                                    <div class="flex text-yellow-500 text-[10px] mb-3">
                                        @for($i = 0; $i < $review->rating; $i++) <i class="fas fa-star"></i> @endfor
                                        @for($i = 0; $i < (5 - $review->rating); $i++) <i class="fas fa-star text-slate-200"></i> @endfor
                                    </div>
                                    <p class="text-[12px] italic text-slate-600">"{{ $review->review_text }}"</p>
                                </div>
                                <p class="mt-4 text-[10px] font-bold text-primary">
                                    {{ $review->customer_name }} @if($review->customer_role) - {{ $review->customer_role }} @endif
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <footer class="p-8 text-center bg-white">
            <div class="mb-10">
                <h4 class="font-extrabold text-gray-900 text-lg mb-2">Pesan Sekarang!</h4>
                <p class="text-xs text-gray-500 mb-6 px-4">Kemudahan mendapatkan produk impian. Pesan sekarang juga.</p>
                <a href="#mainPage" class="bg-primary text-primary-text block w-full py-4 rounded-xl font-bold text-lg animate-cta-btn shadow-md transition hover:bg-opacity-90">
                    LIHAT KATALOG
                </a>
            </div>
            
            <div class="pt-8 border-t border-slate-100">
                <div class="text-2xl font-bold text-gray-900 tracking-tighter mb-2">{{ $landingPage->title }}</div>
                <p class="text-[10px] text-slate-400 font-medium">&copy; {{ date('Y') }} All Rights Reserved. Powered by LP Builder.</p>
            </div>
        </footer>
    </main>

    <!-- Detail Product Overlay Modal -->
    <div id="detailOverlay" class="detail-overlay">
        <div class="max-w-xl mx-auto min-h-screen bg-white relative pb-44">
            <button onclick="hideDetail()" class="fixed top-6 left-6 z-[110] w-12 h-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-primary border border-slate-100 active:scale-90 transition hover:scale-105">
                <i class="fas fa-arrow-left"></i>
            </button>

            <div id="detailHero" class="w-full h-80 bg-gray-100 overflow-hidden relative flex items-center justify-center border-b border-gray-100">
                <!-- dynamic image injected via JS -->
            </div>

            <div class="p-8">
                <div id="detailHeader">
                    <!-- dynamic title & price injected via JS -->
                </div>

                <div class="mt-8">
                    <div class="glass-card p-6 rounded-2xl">
                        <h4 class="font-bold text-gray-900 mb-3 text-sm">Deskripsi Produk</h4>
                        <div id="detailDesc" class="text-sm leading-relaxed text-slate-600 prose prose-sm max-w-none">
                            <!-- dynamic desc injected via JS -->
                        </div>
                    </div>
                </div>

                <!-- Quantity Selector -->
                <div id="detailQtySection" style="margin-top:1.5rem;">
                    <div style="background:#fff; border:1px solid #e2e8f0; padding:1.25rem 1.5rem; border-radius:1.5rem; box-shadow:0 1px 4px rgba(0,0,0,0.08);">
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                            <div>
                                <p style="font-weight:800; font-size:0.7rem; text-transform:uppercase; letter-spacing:0.08em; color:#121212; font-style:italic; margin:0; display:flex; align-items:center; gap:6px;">
                                    <i class="fas fa-cubes" style="color:{{ $themeColor }};"></i>Jumlah Pembelian
                                </p>
                                <p id="detail-qty-label" style="font-size:0.72rem; color:#94a3b8; margin:4px 0 0 0;">Subtotal: —</p>
                            </div>
                            <div style="display:flex; align-items:center; gap:0.5rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:1rem; padding:6px;">
                                <button onclick="changeQtyOverlay(-1)" style="width:2.25rem; height:2.25rem; border-radius:0.625rem; background:#fff; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:900; color:#475569; cursor:pointer; box-shadow:0 1px 3px rgba(0,0,0,0.08); flex-shrink:0;">&minus;</button>
                                <span id="detail-qty-display" style="min-width:2rem; text-align:center; font-size:1.25rem; font-weight:900; color:#121212; user-select:none; display:inline-block;">1</span>
                                <button onclick="changeQtyOverlay(1)" style="width:2.25rem; height:2.25rem; border-radius:0.625rem; background:{{ $themeColor }}; border:none; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:900; color:{{ $primaryTextColor }}; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,0.15); flex-shrink:0;">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="detailAddons" class="mt-8 hidden">
                    <h4 class="font-black text-dark text-sm uppercase tracking-widest mb-4 italic underline decoration-secondary decoration-4 underline-offset-4 pl-2">Add-ons Opsional</h4>
                    <div id="detailAddonsList" class="space-y-3">
                        <!-- dynamic addons injected via JS -->
                    </div>
                </div>
            </div>

            <div class=" left-0 w-full px-6 z-[110] flex justify-center">
                <div class="w-full max-w-xl">
                    <a id="detailCTA" href="#" class="bg-primary text-primary-text flex items-center justify-center w-full py-4 rounded-xl font-bold text-lg text-center shadow-md animate-cta-btn transition hover:bg-opacity-90">
                        BELI SEKARANG
                    </a>
                </div>
            </div>
        </div>
    </div>

    @php
        // Prepare Products Data for Javascript
            $productsData = [];
            foreach($landingPage->products as $p) {
                $checkoutUrl = route('public.checkout', ['slug' => $landingPage->slug, 'product_id' => $p->id]);
                $productsData[$p->id] = [
                    'id' => $p->id,
                    'title' => $p->name,
                    'priceStr' => $p->sale_price > 0 ? 'IDR '.number_format($p->sale_price, 0, ',', '.') : 'IDR '.number_format($p->price, 0, ',', '.'),
                    'oldPriceStr' => $p->sale_price > 0 ? 'IDR '.number_format($p->price, 0, ',', '.') : null,
                    'rawPrice' => $p->sale_price > 0 ? $p->sale_price : $p->price,
                    'img' => $p->image_path ? asset('storage/'.$p->image_path) : null,
                    'desc' => $p->description ?? 'Tidak ada deskripsi rincian untuk produk ini.',
                    'checkoutUrl' => $checkoutUrl,
                    'addons' => $p->addOns->map(function($a) {
                        return [
                            'id' => $a->id,
                            'name' => $a->name,
                            'priceStr' => '+IDR '.number_format($a->price, 0, ',', '.'),
                            'rawPrice' => $a->price,
                        ];
                    })->values()->all(),
                ];
            }
    @endphp

    <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
            const products = {!! json_encode($productsData) !!};

        function showDetail(productId) {
            const p = products[productId];
            if (!p) return;

            // Set Image
            const heroEl = document.getElementById('detailHero');
            if(p.img) {
                heroEl.innerHTML = `<img src="${p.img}" loading="lazy" class="w-full h-full object-cover">`;
            } else {
                heroEl.innerHTML = `<i class="fas fa-box text-6xl text-slate-400"></i>`;
            }

            // Set Title & Price
            let priceHtml = '';
            if(p.oldPriceStr) {
                priceHtml = `<span class="text-slate-400 line-through text-lg mr-2 font-medium">${p.oldPriceStr}</span> ${p.priceStr}`;
            } else {
                priceHtml = p.priceStr;
            }
            
            document.getElementById('detailHeader').innerHTML = `
                <h2 class="text-3xl font-black text-dark leading-tight">${p.title}</h2>
                <div class="mt-2 text-2xl font-bold text-primary">${priceHtml}</div>
            `;
            
            // Set Desc
            document.getElementById('detailDesc').innerHTML = p.desc;
            
            // Set Addons + reset selected
            const addonsContainer = document.getElementById('detailAddons');
            const addonsList = document.getElementById('detailAddonsList');
            if (p.addons && p.addons.length > 0) {
                window._detailAddons = p.addons;
                window._detailSelectedAddons = {};
                addonsList.innerHTML = p.addons.map(a => `
                    <div class="bg-accent p-4 rounded-xl flex justify-between items-center border border-slate-200 shadow-sm">
                        <div>
                            <span class="block text-sm font-semibold text-slate-700">${a.name}</span>
                            <span class="block text-[11px] text-slate-500 mt-1">Harga add-on: ${a.priceStr}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-2 py-1.5">
                            <button onclick="changeAddonQty(${a.id}, -1)" class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-center text-xs font-black text-slate-600">&minus;</button>
                            <span id="addon-qty-${a.id}" class="min-w-[1.5rem] text-center text-sm font-bold text-slate-800">0</span>
                            <button onclick="changeAddonQty(${a.id}, 1)" class="w-7 h-7 rounded-lg bg-primary text-primary-text text-xs font-black flex items-center justify-center shadow">+</button>
                        </div>
                    </div>
                `).join('');
                addonsContainer.classList.remove('hidden');
            } else {
                window._detailAddons = [];
                window._detailSelectedAddons = {};
                addonsList.innerHTML = '';
                addonsContainer.classList.add('hidden');
            }

            // Set CTA Link
            var baseCheckout = p.checkoutUrl;

            window._detailCheckoutBase = baseCheckout;
            window._detailBasePrice = p.rawPrice;
            window._detailQty = 1;
            window._detailAddons = window._detailAddons || [];
            window._detailSelectedAddons = window._detailSelectedAddons || {};

            // Reset qty + subtotal + CTA
            document.getElementById('detail-qty-display').textContent = 1;
            recomputeDetailTotals();

            // Show Overlay
            document.getElementById('detailOverlay').classList.add('detail-active');
            document.body.style.overflow = 'hidden';
        }

        function hideDetail() {
            document.getElementById('detailOverlay').classList.remove('detail-active');
            document.body.style.overflow = 'auto';
        }

        function changeQtyOverlay(delta) {
            window._detailQty = Math.max(1, (window._detailQty || 1) + delta);
            document.getElementById('detail-qty-display').textContent = window._detailQty;
            recomputeDetailTotals();
        }

        function changeAddonQty(addonId, delta) {
            if (!window._detailSelectedAddons) window._detailSelectedAddons = {};
            const current = window._detailSelectedAddons[addonId] || 0;
            const next = Math.max(0, current + delta);
            window._detailSelectedAddons[addonId] = next;

            const qtyEl = document.getElementById('addon-qty-' + addonId);
            if (qtyEl) qtyEl.textContent = next;

            recomputeDetailTotals();
        }

        function recomputeDetailTotals() {
            const basePrice = Number(window._detailBasePrice || 0);
            const qty = Number(window._detailQty || 1);
            let addonsTotal = 0;

            if (Array.isArray(window._detailAddons) && window._detailSelectedAddons) {
                window._detailAddons.forEach(a => {
                    const q = window._detailSelectedAddons[a.id] || 0;
                    if (q > 0 && a.rawPrice) {
                        addonsTotal += Number(a.rawPrice) * q;
                    }
                });
            }

            const subtotal = basePrice * qty + addonsTotal;
            const labelEl = document.getElementById('detail-qty-label');
            if (labelEl) {
                labelEl.textContent = 'Subtotal: IDR ' + subtotal.toLocaleString('id-ID');
            }

            // Build checkout URL with qty + addons info
            let href = window._detailCheckoutBase + '&qty=' + qty;
            if (window._detailSelectedAddons) {
                const parts = Object.entries(window._detailSelectedAddons)
                    .filter(([, v]) => v > 0)
                    .map(([id, v]) => id + ':' + v);
                if (parts.length > 0) {
                    href += '&addons=' + encodeURIComponent(parts.join(','));
                }
            }
            const cta = document.getElementById('detailCTA');
            if (cta) cta.href = href;
        }

        // Testimonial Seamless Marquee
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swiper !== 'undefined' && document.querySelector(".testimonialSwiper")) {
                new Swiper(".testimonialSwiper", {
                    slidesPerView: "auto",
                    spaceBetween: 15,
                    loop: true,
                    speed: 5000,
                    allowTouchMove: false,
                    autoplay: {
                        delay: 0,
                        disableOnInteraction: false,
                    }
                });
            }
        });
    </script>
</body>
</html>
