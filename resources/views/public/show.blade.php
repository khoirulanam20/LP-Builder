<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $landingPage->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    @php
        $themeColor = $landingPage->appearance->theme_color ?? '#34656D';
        // Add dynamic secondary/accent colors based on what fits - for now we hardcode complementary ones based on user reference
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
        .product-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
        .product-card:hover { transform: scale(1.02); }
        
        /* Animasi Berdenyut untuk tombol utama */
        @keyframes custom-pulse {
            0% { box-shadow: 0 0 0 0 {{ $pulseColor }}; }
            70% { box-shadow: 0 0 0 15px {{ $pulseColorZero }}; }
            100% { box-shadow: 0 0 0 0 {{ $pulseColorZero }}; }
        }
        .animate-cta-btn { animation: custom-pulse 2s infinite; }

        /* Seamless Marquee Testimonial */
        .testimonialSwiper .swiper-wrapper {
            transition-timing-function: linear !important;
        }

        /* Shine Effect */
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

        /* Detail Page Overlay */
        .detail-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #FAF8F1;
            z-index: 100;
            overflow-y: auto;
        }
        .detail-active { display: block; animation: slideIn 0.3s ease-out; }
        @keyframes slideIn { from { transform: translateY(100%); } to { transform: translateY(0); } }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(52, 101, 109, 0.1);
        }
    </style>
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
        
        <header class="pt-12 pb-8 px-6 text-center bg-dark text-white rounded-b-[3rem] shadow-xl relative overflow-hidden">
            @if($landingPage->image_path)
                <div class="absolute inset-0 opacity-20 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $landingPage->image_path) }}')"></div>
            @endif

            <div class="relative z-10">
                <div class="mb-6 relative inline-block">
                    @if($landingPage->appearance && $landingPage->appearance->logo_path)
                        <img src="{{ asset('storage/' . $landingPage->appearance->logo_path) }}" alt="Logo" class="w-24 h-24 object-cover rounded-full border-4 border-secondary mx-auto shadow-lg bg-white">
                    @else
                        <!-- Fallback Logo if not provided -->
                        <div class="w-24 h-24 rounded-full border-4 border-secondary mx-auto shadow-lg bg-primary flex items-center justify-center text-white text-4xl font-bold">
                            {{ substr($landingPage->title, 0, 1) }}
                        </div>
                    @endif
                    <div class="absolute bottom-0 right-0 bg-green-500 w-6 h-6 rounded-full border-4 border-dark"></div>
                </div>
                
                <h1 class="text-2xl font-bold tracking-tight">{{ $landingPage->title }}</h1>
                
                @if($landingPage->description)
                    <p class="mt-4 text-secondary font-medium px-4 text-sm">{{ $landingPage->description }}</p>
                @endif
                
                @if($landingPage->appearance && $landingPage->appearance->about_text)
                    <p class="mt-2 text-gray-300 px-4 text-xs">{{ $landingPage->appearance->about_text }}</p>
                @endif
                
                @if($landingPage->appearance && $landingPage->appearance->social_links)
                    <div class="flex justify-center flex-wrap gap-6 mt-6">
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
                            <a href="{{ $url }}" target="_blank" class="text-secondary hover:text-white transition text-xl">
                                <i class="fab {{ $icon }}"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </header>

        <section class="px-4 py-8 space-y-4">
            <h2 class="text-center font-black text-dark text-xl uppercase tracking-widest mb-6 italic underline decoration-secondary decoration-4 underline-offset-8">Katalog Produk</h2>

            @forelse($landingPage->products as $product)
                <div onclick="showDetail({{ $product->id }})" class="product-card group relative bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex items-center p-3 hover:border-primary">
                    <div class="w-20 h-20 bg-accent rounded-xl flex-shrink-0 flex items-center justify-center text-primary text-2xl overflow-hidden shadow-inner">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
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
                    <div class="ml-2 border border-slate-200 text-slate-400 p-3 rounded-full group-hover:bg-primary group-hover:text-white transition">
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
                <p class="text-center text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6">Real Reviews dari Pembeli</p>
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
                <h4 class="font-black text-dark text-xl mb-3 italic">Pesan Sekarang & Buktikan Kualitasnya!</h4>
                <p class="text-xs text-slate-500 mb-8 px-4">Kemudahan mendapatkan produk impian. Tinggal tap dan selesaikan pesanan Anda sekarang juga.</p>
                <a href="#mainPage" class="btn-shine bg-primary text-white block w-full py-5 rounded-2xl font-black text-xl animate-cta-btn shadow-2xl tracking-tight">
                    LIHAT KATALOG
                </a>
            </div>
            
            <div class="pt-8 border-t border-slate-100">
                <div class="text-2xl font-bold text-primary tracking-tighter mb-2">{{ $landingPage->title }}</div>
                <p class="text-[10px] text-slate-400 font-medium">&copy; {{ date('Y') }} All Rights Reserved. Powered by LP Builder.</p>
            </div>
        </footer>
    </main>

    <!-- Detail Product Overlay Modal -->
    <div id="detailOverlay" class="detail-overlay">
        <div class="max-w-xl mx-auto min-h-screen bg-white relative pb-32">
            <button onclick="hideDetail()" class="fixed top-6 left-6 z-[110] w-12 h-12 bg-white rounded-2xl shadow-xl flex items-center justify-center text-primary border border-slate-100 active:scale-90 transition hover:scale-105">
                <i class="fas fa-arrow-left"></i>
            </button>

            <div id="detailHero" class="w-full h-80 bg-slate-200 overflow-hidden rounded-b-[3rem] shadow-lg relative flex items-center justify-center">
                <!-- dynamic image injected via JS -->
            </div>

            <div class="p-8">
                <div id="detailHeader">
                    <!-- dynamic title & price injected via JS -->
                </div>

                <div class="mt-8">
                    <div class="glass-card p-6 rounded-3xl">
                        <h4 class="font-bold text-primary mb-3 uppercase tracking-wider text-xs italic"><i class="fas fa-info-circle mr-2"></i>Deskripsi Produk</h4>
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
                                <button onclick="changeQtyOverlay(1)" style="width:2.25rem; height:2.25rem; border-radius:0.625rem; background:{{ $themeColor }}; border:none; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:900; color:#fff; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,0.15); flex-shrink:0;">+</button>
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

            <div class="fixed bottom-8 left-0 w-full px-6 z-[110] flex justify-center">
                <div class="w-full max-w-xl">
                    <a id="detailCTA" href="#" class="btn-shine bg-primary text-white flex items-center justify-center w-full py-5 rounded-2xl font-black text-xl text-center shadow-[0_20px_40px_rgba(52,101,109,0.3)] animate-cta-btn">
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
                        'name' => $a->name,
                        'priceStr' => '+IDR '.number_format($a->price, 0, ',', '.')
                    ];
                })
            ];
        }
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const products = {!! json_encode($productsData) !!};

        function showDetail(productId) {
            const p = products[productId];
            if (!p) return;

            // Set Image
            const heroEl = document.getElementById('detailHero');
            if(p.img) {
                heroEl.innerHTML = `<img src="${p.img}" class="w-full h-full object-cover">`;
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
            
            // Set Addons
            const addonsContainer = document.getElementById('detailAddons');
            const addonsList = document.getElementById('detailAddonsList');
            if (p.addons && p.addons.length > 0) {
                addonsList.innerHTML = p.addons.map(a => `
                    <div class="bg-accent p-4 rounded-xl flex justify-between items-center border border-slate-200 shadow-sm">
                        <span class="text-sm font-semibold text-slate-700">${a.name}</span>
                        <span class="text-xs font-bold text-white bg-primary px-3 py-1.5 rounded-lg shadow">${a.priceStr}</span>
                    </div>
                `).join('');
                addonsContainer.classList.remove('hidden');
            } else {
                addonsList.innerHTML = '';
                addonsContainer.classList.add('hidden');
            }

            // Set CTA Link
            var baseCheckout = p.checkoutUrl;
            var currentQty = 1;
            var currentBasePrice = p.rawPrice;

            // Reset qty
            document.getElementById('detail-qty-display').textContent = 1;
            document.getElementById('detail-qty-label').textContent = 'Subtotal: IDR ' + Number(p.rawPrice).toLocaleString('id-ID');
            document.getElementById('detailCTA').href = baseCheckout + '&qty=1';

            window._detailCheckoutBase = baseCheckout;
            window._detailBasePrice = p.rawPrice;
            window._detailQty = 1;

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
            document.getElementById('detail-qty-label').textContent = 'Subtotal: IDR ' + (Number(window._detailBasePrice || 0) * window._detailQty).toLocaleString('id-ID');
            document.getElementById('detailCTA').href = window._detailCheckoutBase + '&qty=' + window._detailQty;
        }

        // Testimonial Seamless Marquee
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
    </script>
</body>
</html>
