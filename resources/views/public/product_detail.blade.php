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
                <a href="{{ route('public.checkout', ['slug' => $landingPage->slug, 'product_id' => $product->id]) }}" class="btn-shine bg-primary text-white flex items-center justify-center w-full py-5 rounded-2xl font-black text-xl text-center shadow-[0_20px_40px_rgba(52,101,109,0.3)] animate-cta-btn">
                    <i class="fas fa-shopping-cart mr-2"></i> BELI SEKARANG
                </a>
            </div>
        </div>
    </div>
</body>
</html>
