<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ \App\Models\SystemSetting::where('key', 'site_name')->value('value') ?? 'My-LP Builder' }} - Solusi Landing Page Jualan No. 1</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Scripts & Styles via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Outfit', 'sans-serif'],
                        },
                        colors: {
                            primary: {
                                50: '#f5f3ff',
                                100: '#ede9fe',
                                200: '#ddd6fe',
                                300: '#c4b5fd',
                                400: '#a78bfa',
                                500: '#8b5cf6',
                                600: '#7c3aed',
                                700: '#6d28d9',
                                800: '#5b21b6',
                                900: '#4c1d95',
                            },
                        },
                    },
                },
            }
        </script>

        <style>
            .glass {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .glass-dark {
                background: rgba(15, 23, 42, 0.8);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            .text-gradient {
                background: linear-gradient(135deg, #6d28d9 0%, #db2777 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            .bg-gradient-mesh {
                background-color: #f8fafc;
                background-image: 
                    radial-gradient(at 0% 0%, rgba(109, 40, 217, 0.1) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(219, 39, 119, 0.1) 0px, transparent 50%),
                    radial-gradient(at 50% 100%, rgba(14, 165, 233, 0.1) 0px, transparent 50%);
            }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
            .animate-float {
                animation: float 4s ease-in-out infinite;
            }
            .step-number {
                background: linear-gradient(135deg, #8b5cf6 0%, #d946ef 100%);
            }
        </style>
    </head>
    <body class="bg-gradient-mesh text-slate-900 overflow-x-hidden">

        <!-- Navigation -->
        <nav x-data="{ open: false, scrolled: false }" 
             @scroll.window="scrolled = (window.pageYOffset > 20) ? true : false"
             :class="scrolled ? 'glass py-3' : 'bg-transparent py-5'"
             class="fixed w-full z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-200">
                            <i class="fas fa-rocket text-white"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tight text-slate-900">My-LP<span class="text-primary-600">.</span></span>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#features" class="text-sm font-semibold text-slate-600 hover:text-primary-600 transition">Fitur</a>
                        <a href="#how-it-works" class="text-sm font-semibold text-slate-600 hover:text-primary-600 transition">Cara Kerja</a>
                        <a href="#pricing" class="text-sm font-semibold text-slate-600 hover:text-primary-600 transition">Harga</a>
                        <div class="h-6 w-px bg-slate-200 mx-2"></div>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-slate-900 hover:text-primary-600 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-primary-600 transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-primary-600 text-white px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-primary-200 hover:bg-primary-700 hover:-translate-y-0.5 transition active:scale-95">Mulai Sekarang</a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center">
                        <button @click="open = !open" class="text-slate-600 hover:text-primary-600 transition p-2">
                            <i :class="open ? 'fas fa-times' : 'fas fa-bars'" class="text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Content -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="md:hidden glass border-t border-slate-100 absolute w-full shadow-xl">
                <div class="px-4 pt-2 pb-6 space-y-2">
                    <a href="#features" class="block px-3 py-3 text-base font-semibold text-slate-700 rounded-xl hover:bg-primary-50">Fitur</a>
                    <a href="#how-it-works" class="block px-3 py-3 text-base font-semibold text-slate-700 rounded-xl hover:bg-primary-50">Cara Kerja</a>
                    <a href="#pricing" class="block px-3 py-3 text-base font-semibold text-slate-700 rounded-xl hover:bg-primary-50">Harga</a>
                    <div class="pt-4 flex flex-col gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="w-full text-center bg-primary-600 text-white px-6 py-3 rounded-xl font-bold">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="w-full text-center border border-slate-200 text-slate-700 px-6 py-3 rounded-xl font-bold">Log in</a>
                            <a href="{{ route('register') }}" class="w-full text-center bg-primary-600 text-white px-6 py-3 rounded-xl font-bold">Daftar Sekarang</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="space-y-8 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-50 border border-primary-100 text-primary-600 text-sm font-bold animate-fade-in">
                            <span class="w-2 h-2 rounded-full bg-primary-600 animate-pulse"></span>
                            Platform Landing Page Jualan Terbaik 2026
                        </div>
                        <h1 class="text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] tracking-tight">
                            Bangun Landing Page <span class="text-gradient">Jualanmu</span> dalam Hitungan Menit.
                        </h1>
                        <p class="text-xl text-slate-500 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                            Lupakan ribetnya coding. Buat landing page profesional, integrasi pembayaran otomatis, dan kelola pesanan hanya dalam satu dashboard yang intuitif.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="{{ route('register') }}" class="bg-primary-600 text-white px-8 py-4 rounded-2xl text-lg font-black shadow-[0_20px_40px_rgba(109,40,217,0.3)] hover:bg-primary-700 hover:-translate-y-1 transition transform active:scale-95 flex items-center justify-center gap-2">
                                Mulai Sekarang <i class="fas fa-arrow-right text-sm"></i>
                            </a>
                            <a href="#how-it-works" class="bg-white text-slate-700 border border-slate-200 px-8 py-4 rounded-2xl text-lg font-black hover:bg-slate-50 transition flex items-center justify-center gap-2">
                                <i class="fas fa-play text-xs text-primary-600"></i> Lihat Demo
                            </a>
                        </div>
                        <div class="flex items-center gap-6 justify-center lg:justify-start pt-4">
                            <div class="flex -space-x-3">
                                <img class="w-10 h-10 rounded-full border-2 border-white shadow-sm" src="https://i.pravatar.cc/150?u=1" alt="">
                                <img class="w-10 h-10 rounded-full border-2 border-white shadow-sm" src="https://i.pravatar.cc/150?u=2" alt="">
                                <img class="w-10 h-10 rounded-full border-2 border-white shadow-sm" src="https://i.pravatar.cc/150?u=3" alt="">
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 shadow-sm">+1k</div>
                            </div>
                            <p class="text-xs font-semibold text-slate-400">Bergabung dengan 1,200+ seller lainnya</p>
                        </div>
                    </div>
                    <div class="relative lg:-mr-20">
                        <!-- Background Glow -->
                        <div class="absolute -top-20 -left-20 w-80 h-80 bg-primary-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                        <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
                        
                        <div class="relative animate-float">
                            <img src="{{ asset('images/platform_mockup.png') }}" alt="My-LP Dashboard Mockup" class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white">
                            
                            <!-- Floating Card Stats -->
                            <div class="absolute -bottom-10 -left-10 glass p-5 rounded-3xl shadow-xl border border-white/50 animate-float order-last hidden sm:block">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600">
                                        <i class="fas fa-chart-line text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Total Sales</div>
                                        <div class="text-xl font-black text-slate-900 leading-none">Rp 12.5M+</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Card User -->
                            <div class="absolute -top-10 -right-10 glass p-4 rounded-3xl shadow-xl border border-white/50 animate-float animation-delay-2000 hidden sm:block">
                                <div class="flex items-center gap-3">
                                    <img src="https://i.pravatar.cc/100?u=4" alt="" class="w-10 h-10 rounded-full">
                                    <div>
                                        <div class="text-[10px] font-bold text-slate-900 leading-none">New Order!</div>
                                        <div class="text-[12px] font-black text-primary-600">IDR 450.000</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Counter -->
        <section class="py-12 bg-white/50 border-y border-slate-100 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-16 text-center">
                    <div>
                        <div class="text-4xl font-black text-slate-900 mb-1">5,000+</div>
                        <div class="text-sm font-bold text-slate-400 uppercase tracking-widest">LP Dibuat</div>
                    </div>
                    <div>
                        <div class="text-4xl font-black text-slate-900 mb-1">Rp 200B+</div>
                        <div class="text-sm font-bold text-slate-400 uppercase tracking-widest">Revenue Diproses</div>
                    </div>
                    <div>
                        <div class="text-4xl font-black text-slate-900 mb-1">1.2jt+</div>
                        <div class="text-sm font-bold text-slate-400 uppercase tracking-widest">Total Kunjungan</div>
                    </div>
                    <div>
                        <div class="text-4xl font-black text-slate-900 mb-1">99.9%</div>
                        <div class="text-sm font-bold text-slate-400 uppercase tracking-widest">Uptime Server</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                    <h2 class="text-primary-600 text-sm font-black uppercase tracking-[0.2em]">Fitur Utama</h2>
                    <h3 class="text-4xl lg:text-5xl font-black text-slate-900 leading-tight">Dirancang Khusus untuk <span class="bg-primary-50 px-2 rounded-lg italic">Pertumbuhan</span> Bisnis Anda.</h3>
                    <p class="text-lg text-slate-500">Semua yang Anda butuhkan untuk berjualan produk digital maupun fisik ada di sini.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all group">
                        <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center text-primary-600 text-2xl mb-8 group-hover:bg-primary-600 group-hover:text-white transition-colors">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Cepat & Instan</h4>
                        <p class="text-slate-500 leading-relaxed">Bangun landing page produk Anda tanpa ribet. Cukup masukkan detail produk, gambar, dan deskripsi, sistem akan membuatkan tampilannya untuk Anda.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all group">
                        <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 text-2xl mb-8 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Pembayaran Otomatis</h4>
                        <p class="text-slate-500 leading-relaxed">Terintegrasi langsung dengan Midtrans. Terima pembayaran via QRIS, E-Wallet, dan Bank Transfer dengan verifikasi otomatis secara real-time.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all group">
                        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Delivery Otomatis</h4>
                        <p class="text-slate-500 leading-relaxed">Sistem akan secara otomatis mengirimkan link produk, akses member, atau file pesanan langsung ke email pelanggan begitu pembayaran terkonfirmasi.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all group">
                        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 text-2xl mb-8 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Analitik Mendalam</h4>
                        <p class="text-slate-500 leading-relaxed">Pantau perilaku pengunjung melalui rasio klik tombol, dari mana traffic berasal, hingga tren pendapatan harian Anda dalam dashboard cantik.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all group">
                        <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center text-pink-600 text-2xl mb-8 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Custom Tampilan</h4>
                        <p class="text-slate-500 leading-relaxed">Ubah warna tema, font, dan elemen visual lainnya hanya dengan sekali klik untuk menyesuaikan dengan branding bisnis Anda.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:-translate-y-2 transition-all group">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-8 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 class="text-xl font-black text-slate-900 mb-4 tracking-tight">Optimasi Mobile</h4>
                        <p class="text-slate-500 leading-relaxed">90% pembeli berasal dari smartphone. Landing page Anda akan dibuat super ringan dan responsif untuk memaksimalkan angka penjualan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it Works Section -->
        <section id="how-it-works" class="py-24 lg:py-32 bg-slate-900 text-white rounded-[4rem] mx-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-primary-600/20 filter blur-3xl rounded-full"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-pink-600/10 filter blur-3xl rounded-full"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-3xl mx-auto mb-24 space-y-4">
                    <h2 class="text-primary-400 text-sm font-black uppercase tracking-[0.2em]">Cara Kerja</h2>
                    <h3 class="text-4xl lg:text-5xl font-black leading-tight">Hanya Perlu 3 Langkah untuk Online.</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-16 relative">
                    <!-- Progress Line (Desktop) -->
                    <div class="hidden md:block absolute top-12 left-[15%] right-[15%] h-1 bg-gradient-to-r from-primary-600 via-pink-600 to-primary-600 opacity-20"></div>

                    <!-- Step 1 -->
                    <div class="relative text-center space-y-8">
                        <div class="w-24 h-24 step-number rounded-full flex items-center justify-center text-3xl font-black mx-auto shadow-2xl shadow-primary-500/30">1</div>
                        <div>
                            <h4 class="text-2xl font-black mb-4">Daftar & Login</h4>
                            <p class="text-slate-400 leading-relaxed">Buat akun Anda dalam 30 detik. Akun Anda langsung siap digunakan tanpa aktivasi manual yang lama.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative text-center space-y-8">
                        <div class="w-24 h-24 step-number rounded-full flex items-center justify-center text-3xl font-black mx-auto shadow-2xl shadow-pink-500/30">2</div>
                        <div>
                            <h4 class="text-2xl font-black mb-4">Input Produk</h4>
                            <p class="text-slate-400 leading-relaxed">Upload gambar produk, tulis deskripsi yang memikat, dan atur integrasi pembayaran Midtrans Anda.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative text-center space-y-8">
                        <div class="w-24 h-24 step-number rounded-full flex items-center justify-center text-3xl font-black mx-auto shadow-2xl shadow-primary-500/30">3</div>
                        <div>
                            <h4 class="text-2xl font-black mb-4">Sebarkan Link</h4>
                            <p class="text-slate-400 leading-relaxed">Landing page Anda siap! Bagikan link ke media sosial atau pasang iklan untuk mulai menerima transferan otomatis.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-24 text-center">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-3 bg-white text-slate-900 px-10 py-5 rounded-2xl font-black hover:bg-slate-50 transition active:scale-95 shadow-xl">
                        Mulai Gratis Sekarang <i class="fas fa-chevron-right text-sm"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-24 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                    <h2 class="text-primary-600 text-sm font-black uppercase tracking-[0.2em]">Pilihan Paket</h2>
                    <h3 class="text-4xl lg:text-5xl font-black text-slate-900 leading-tight">Harga Transparan Tanpa Biaya Tersembunyi.</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    <!-- Starter Plan -->
                    <div class="bg-white p-12 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-xl transition-all">
                        <div>
                            <div class="text-xs font-black uppercase text-slate-400 tracking-widest mb-2 text-center">Starter Cloud</div>
                            <div class="text-4xl font-black text-slate-900 text-center mb-8">Rp 0<span class="text-sm font-bold text-slate-400">/bulan</span></div>
                            <ul class="space-y-4 mb-10">
                                <li class="flex items-center gap-3 text-slate-600 font-medium">
                                    <i class="fas fa-check-circle text-emerald-500"></i> 1 Landing Page Aktif
                                </li>
                                <li class="flex items-center gap-3 text-slate-600 font-medium">
                                    <i class="fas fa-check-circle text-emerald-500"></i> Maks. 3 Produk
                                </li>
                                <li class="flex items-center gap-3 text-slate-600 font-medium">
                                    <i class="fas fa-check-circle text-emerald-500"></i> Statistik Standar
                                </li>
                                <li class="flex items-center gap-3 text-slate-300 font-medium italic">
                                    <i class="fas fa-times-circle"></i> Custom Subdomain
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('register') }}" class="w-full text-center border-2 border-slate-100 py-4 rounded-2xl font-black text-slate-600 hover:border-primary-600 hover:text-primary-600 transition">Pilih Paket</a>
                    </div>

                    <!-- Pro Plan -->
                    <div class="bg-slate-900 p-12 rounded-[3rem] shadow-2xl shadow-primary-500/20 relative overflow-hidden flex flex-col justify-between border-4 border-primary-600">
                        <div class="absolute top-8 right-8 bg-primary-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Recommended</div>
                        <div>
                            <div class="text-xs font-black uppercase text-primary-400 tracking-widest mb-2 text-center">Business Pro</div>
                            <div class="text-4xl font-black text-white text-center mb-8">Rp 99rb<span class="text-sm font-bold text-slate-500">/bulan</span></div>
                            <ul class="space-y-4 mb-10">
                                <li class="flex items-center gap-3 text-slate-300 font-medium">
                                    <i class="fas fa-check-circle text-primary-500 text-lg"></i> Landing Page Sepuasnya
                                </li>
                                <li class="flex items-center gap-3 text-slate-300 font-medium">
                                    <i class="fas fa-check-circle text-primary-500 text-lg"></i> Produk Tak Terbatas
                                </li>
                                <li class="flex items-center gap-3 text-slate-300 font-medium">
                                    <i class="fas fa-check-circle text-primary-500 text-lg"></i> Statistik Super Lengkap
                                </li>
                                <li class="flex items-center gap-3 text-slate-300 font-medium">
                                    <i class="fas fa-check-circle text-primary-500 text-lg"></i> Integrasi Midtrans Prioritas
                                </li>
                                <li class="flex items-center gap-3 text-slate-300 font-medium">
                                    <i class="fas fa-check-circle text-primary-500 text-lg"></i> Custom Email Notifikasi
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('register') }}" class="w-full text-center bg-primary-600 py-4 rounded-2xl font-black text-white shadow-lg shadow-primary-700/50 hover:bg-primary-500 hover:-translate-y-1 transition transform active:scale-95">Upgrade Sekarang</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 bg-gradient-to-br from-primary-600 to-pink-600 mx-4 rounded-[4rem] text-center text-white mb-10">
            <div class="max-w-4xl mx-auto px-4 space-y-8">
                <h3 class="text-4xl lg:text-6xl font-black leading-tight tracking-tight">Siap Meroketkan Penjualan Anda Hari Ini?</h3>
                <p class="text-xl text-white/80 max-w-2xl mx-auto font-medium">Daftar sekarang dan rasakan kemudahan mengelola bisnis online dalam satu genggaman.</p>
                <div class="pt-6">
                    <a href="{{ route('register') }}" class="bg-white text-primary-600 px-12 py-6 rounded-2xl text-xl font-black shadow-2xl hover:-translate-y-2 transition transform active:scale-95">
                        Bangun Landing Page Anda Gratis <i class="fas fa-rocket ml-2"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white pt-20 pb-10 border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                    <div class="col-span-1 md:col-span-1 space-y-6 text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center shadow-lg shadow-primary-200">
                                <i class="fas fa-rocket text-white text-xs"></i>
                            </div>
                            <span class="text-xl font-black tracking-tight text-slate-900">My-LP<span class="text-primary-600">.</span></span>
                        </div>
                        <p class="text-sm text-slate-400 leading-relaxed font-medium capitalize">
                            Solusi terbaik bagi entrepreneur muda untuk membangun keberadaan digital yang profesional dan menguntungkan.
                        </p>
                        <div class="flex items-center justify-center md:justify-start gap-4">
                            <a href="#" class="w-8 h-8 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:border-primary-600 transition"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:border-primary-600 transition"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="w-8 h-8 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary-600 hover:border-primary-600 transition"><i class="fab fa-facebook"></i></a>
                        </div>
                    </div>
                    
                    <div class="text-center md:text-left">
                        <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6">Produk</h5>
                        <ul class="space-y-4 text-sm font-bold text-slate-400">
                            <li><a href="#" class="hover:text-primary-600 transition">Template</a></li>
                            <li><a href="#" class="hover:text-primary-600 transition">Integrasi Midtrans</a></li>
                            <li><a href="#" class="hover:text-primary-600 transition">Email Marketing</a></li>
                        </ul>
                    </div>

                    <div class="text-center md:text-left">
                        <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6">Perusahaan</h5>
                        <ul class="space-y-4 text-sm font-bold text-slate-400">
                            <li><a href="#" class="hover:text-primary-600 transition">Tentang Kami</a></li>
                            <li><a href="#" class="hover:text-primary-600 transition">Hubungi Kami</a></li>
                            <li><a href="#" class="hover:text-primary-600 transition">Privasi</a></li>
                        </ul>
                    </div>

                    <div class="text-center md:text-left">
                        <h5 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-6">Bantuan</h5>
                        <ul class="space-y-4 text-sm font-bold text-slate-400">
                            <li><a href="#" class="hover:text-primary-600 transition">Pusat Bantuan</a></li>
                            <li><a href="#" class="hover:text-primary-600 transition">Dokumentasi API</a></li>
                            <li><a href="#" class="hover:text-primary-600 transition">Status Server</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="pt-10 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">&copy; 2026 Firstudio LP-Builder. All Rights Reserved.</p>
                    <div class="flex items-center gap-4">
                        <img src="https://midtrans.com/assets/image/logo-midtrans.svg" alt="Midtrans" class="h-4 opacity-30">
                        <div class="h-4 w-px bg-slate-100"></div>
                        <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel" class="h-4 opacity-30">
                    </div>
                </div>
            </div>
        </footer>

    </body>
</html>
