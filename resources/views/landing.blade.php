<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="puskesmas">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang - {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', system-ui, sans-serif; color: #0d1c2f; background: #f8f9ff; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Manrope', system-ui, sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .glass { background: rgba(255,255,255,0.75); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        .glass-scrolled { background: rgba(255,255,255,0.9); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-bottom: 1px solid rgba(0,0,0,0.05); }
        .card-hover { transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1); }
        .card-hover:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 20px 60px -15px rgba(0,104,95,0.2); }
        .btn-press:active { transform: scale(0.95); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .gradient-mint { background: linear-gradient(135deg, #e6f5f3 0%, #f0faf8 50%, #f8f9ff 100%); }
        .hero-gradient { background: linear-gradient(165deg, #f8f9ff 0%, #e6f5f3 60%, #f0faf8 100%); }
        .float-anim { animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }
        @keyframes pulse-dot { 0%,100% { opacity: 1; } 50% { opacity: 0.5; } }

    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 glass transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#00685f] text-3xl">local_hospital</span>
                    <span class="font-heading text-xl lg:text-2xl font-extrabold text-[#00685f] tracking-tight">{{ config('app.name') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('cek-resep') }}" class="inline-flex items-center gap-2 font-body text-sm font-semibold text-[#0d1c2f]/60 hover:text-[#00685f] px-4 py-2 transition-colors">
                        <span class="material-symbols-outlined text-base">medication</span>
                        Cek Resep
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 font-body text-sm font-semibold text-[#0d1c2f]/60 hover:text-[#00685f] px-4 py-2 transition-colors">
                        <span class="material-symbols-outlined text-base">login</span>
                        Login
                    </a>
                </div>
        </div>
    </nav>

    <!-- HERO -->
    <section id="hero" class="hero-gradient pt-28 lg:pt-36 pb-28 lg:pb-40 relative overflow-hidden">
        <div class="absolute top-20 right-0 w-96 h-96 bg-[#00685f]/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-[#00685f]/5 rounded-full blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="space-y-6 lg:space-y-7">
                    <div class="inline-flex items-center gap-2 bg-[#00685f]/10 text-[#00685f] font-body text-sm font-semibold px-4 py-2 rounded-full">
                        <span class="w-2 h-2 bg-[#00685f] rounded-full pulse-dot"></span>
                        Kesehatan Masyarakat Prioritas Kami
                    </div>
                    <h1 class="font-heading text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-[#0d1c2f] leading-[1.1] tracking-tight">
                        Melayani dengan <br><span class="text-[#00685f]">Sepenuh Hati</span> untuk Masyarakat Sehat
                    </h1>
                    <p class="font-body text-base lg:text-lg text-[#0d1c2f]/60 leading-relaxed max-w-xl">
                        Melayani kesehatan masyarakat dengan sepenuh hati. Siap memberikan pelayanan terbaik untuk Anda dan keluarga.
                    </p>
                    <div class="flex flex-wrap gap-3 pt-2">
                        @if($status['bisa_daftar'])
                        <a href="{{ route('daftar.form') }}" class="inline-flex items-center gap-2.5 bg-[#00685f] text-white font-body font-semibold text-sm lg:text-base px-6 lg:px-8 py-3.5 lg:py-4 rounded-full hover:bg-[#005a52] transition-all duration-300 shadow-soft-lg hover:shadow-tonal-lg btn-press">
                            <span class="material-symbols-outlined text-lg lg:text-xl">edit_note</span>
                            Daftar Antrean Online
                        </a>
                        @else
                        <a href="#" onclick="document.getElementById('modal-tutup').showModal(); return false;" class="inline-flex items-center gap-2.5 bg-[#00685f] text-white font-body font-semibold text-sm lg:text-base px-6 lg:px-8 py-3.5 lg:py-4 rounded-full hover:bg-[#005a52] transition-all duration-300 shadow-soft-lg hover:shadow-tonal-lg btn-press">
                            <span class="material-symbols-outlined text-lg lg:text-xl">edit_note</span>
                            Daftar Antrean Online
                        </a>
                        @endif
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-[#00685f]/10 to-[#00685f]/5 rounded-[40px] p-2">
                        <div class="bg-white/40 rounded-[36px] overflow-hidden shadow-soft-lg">
                            <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=600&q=80" alt="Dokter dan pasien" class="w-full h-auto object-cover rounded-[36px]">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- MODAL TUTUP --}}
    <dialog id="modal-tutup" class="modal">
        <div class="modal-box text-center">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5" style="background:rgba(221,140,30,0.1)">
                <span class="material-symbols-outlined text-5xl" style="color:#dd8c1e">cancel</span>
            </div>
            <h3 class="text-xl font-bold mb-2">Puskesmas Tutup</h3>
            <p class="text-base opacity-70">{{ $status['pesan'] }}</p>
            <div class="mt-8">
                <button class="btn px-8" style="background:#00685f;color:white;border:none" onclick="document.getElementById('modal-tutup').close()">Tutup</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button></button></form>
    </dialog>

    <script>
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 20) {
                navbar.classList.remove('glass');
                navbar.classList.add('glass-scrolled');
            } else {
                navbar.classList.remove('glass-scrolled');
                navbar.classList.add('glass');
            }
        });

        document.querySelectorAll('.btn-press').forEach(function(btn) {
            btn.addEventListener('mousedown', function() { this.style.transform = 'scale(0.95)'; });
            btn.addEventListener('mouseup', function() { this.style.transform = 'scale(1)'; });
            btn.addEventListener('mouseleave', function() { this.style.transform = 'scale(1)'; });
        });

        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('nav a[href^="#"]');
        window.addEventListener('scroll', function() {
            let current = '';
            sections.forEach(function(section) {
                const top = section.offsetTop - 150;
                if (window.scrollY >= top) { current = section.getAttribute('id'); }
            });
            navLinks.forEach(function(link) {
                link.classList.remove('text-[#00685f]');
                link.classList.add('text-[#0d1c2f]/70');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.remove('text-[#0d1c2f]/70');
                    link.classList.add('text-[#00685f]');
                }
            });
        });
    </script>
</body>
</html>
