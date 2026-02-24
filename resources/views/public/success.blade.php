<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - {{ $landingPage->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    @php
        $themeColor = $landingPage->appearance->theme_color ?? '#34656D';
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
        .glass-card { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(52, 101, 109, 0.1); }
    </style>
</head>
<body class="text-slate-800 font-sans min-h-screen relative py-10 flex items-center justify-center">

    <div class="max-w-xl w-full mx-auto px-4">
        <div class="bg-white shadow-2xl border border-slate-100 rounded-[3rem] overflow-hidden relative pb-10">
            
            <div class="bg-green-500 text-white p-8 pt-12 text-center rounded-b-[3rem] shadow-lg relative">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-green-400 shadow-inner">
                    <i class="fas fa-check text-4xl text-green-500"></i>
                </div>
                <h1 class="text-3xl font-black tracking-tight mb-2">Terima Kasih!</h1>
                <p class="text-sm text-green-100 font-medium">Pembayaran Anda telah berhasil diverifikasi.</p>
            </div>

            <div class="p-8">
                @if(session('review_success'))
                    <div class="bg-green-50 border border-green-200 text-green-600 p-4 rounded-2xl mb-6 text-center shadow-sm">
                        <i class="fas fa-star text-yellow-400 mr-2 text-xl"></i>
                        <span class="font-bold">{{ session('review_success') }}</span>
                    </div>
                @else
                    <div class="glass-card rounded-3xl p-6 mb-8 text-center text-sm text-slate-600 leading-relaxed shadow-sm uppercase font-bold tracking-tight">
                        Halo! Jangan lupa cek emailnya ya. Kalau belum muncul di inbox, coba intip folder Spam sebentar.
                    </div>

                    <div class="bg-accent border border-secondary p-6 rounded-3xl mb-8 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mt-2 -mr-2 text-primary opacity-10">
                            <i class="fas fa-comment-dots text-8xl"></i>
                        </div>
                        <h2 class="font-bold text-primary mb-3 uppercase tracking-wider text-xs italic relative z-10"><i class="fas fa-star mr-2"></i>Berikan Ulasan Pembeli</h2>
                        <p class="text-xs text-slate-500 mb-4 relative z-10">Bantu calon pembeli lain dengan membagikan pengalaman singkat Anda setelah membeli produk ini.</p>
                        
                        <form action="{{ route('public.review.submit', $landingPage->slug) }}" method="POST" class="relative z-10 space-y-4">
                            @csrf
                            <div>
                                <input type="text" name="customer_name" required placeholder="Nama Anda (Cth: Budi S.)" class="w-full bg-white border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary px-4 py-3 text-sm font-medium outline-none transition">
                            </div>
                            <div>
                                <input type="text" name="customer_role" placeholder="Pekerjaan / Jabatan (Opsional, Cth: Mahasiswa)" class="w-full bg-white border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary px-4 py-3 text-sm font-medium outline-none transition">
                            </div>
                            <div>
                                <select name="rating" class="w-full bg-white border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary px-4 py-3 text-sm font-medium outline-none transition text-slate-600" required>
                                    <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                                    <option value="4">⭐⭐⭐⭐ Puas</option>
                                    <option value="3">⭐⭐⭐ Cukup</option>
                                    <option value="2">⭐⭐ Kurang</option>
                                    <option value="1">⭐ Sangat Kurang</option>
                                </select>
                            </div>
                            <div>
                                <textarea name="review_text" rows="3" required placeholder="Tuliskan ulasan singkat Anda di sini..." class="w-full bg-white border border-slate-200 rounded-xl focus:border-primary focus:ring-1 focus:ring-primary px-4 py-3 text-sm font-medium outline-none transition"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-primary text-white font-bold py-3.5 rounded-xl shadow-md hover:opacity-90 transition active:scale-95 text-sm tracking-wide">
                                KIRIM ULASAN
                            </button>
                        </form>
                    </div>
                @endif

                <a href="{{ route('public.show', $slug) }}" class="block w-full bg-dark text-white font-bold py-4 rounded-2xl hover:bg-black transition text-center shadow-lg hover:-translate-y-1 transform">
                    <i class="fas fa-home mr-2"></i> Kembali ke Katalog
                </a>
                
                <div class="mt-8 text-center text-[10px] font-medium text-slate-400">
                    &copy; {{ date('Y') }} All Rights Reserved.<br>Secured by LP Builder.
                </div>
            </div>
        </div>
    </div>
</body>
</html>
