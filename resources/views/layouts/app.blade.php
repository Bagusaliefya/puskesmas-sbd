@php $jam = statusPuskesmas(); @endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="puskesmas">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        .sidebar-overlay {
            opacity: 0;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 40;
            pointer-events: none;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }

        @media (max-width: 1023px) {
            .app-sidebar {
                position: fixed;
                left: -16rem;
                top: 0;
                height: 100%;
                width: 16rem;
                z-index: 50;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                background: oklch(100% 0 0);
                border-right: 1px solid oklch(93% 0.006 268);
                overflow-y: auto;
            }
            [data-theme="puskesmas-dark"] .app-sidebar {
                background: oklch(14% 0.018 260);
                border-right-color: oklch(18% 0.022 260);
            }
            .app-sidebar.open { left: 0; }
            .main-content { margin-left: 0 !important; }
        }

        @media (min-width: 1024px) {
            .app-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100%;
                width: 16rem;
                z-index: 30;
                background: oklch(100% 0 0);
                border-right: 1px solid oklch(93% 0.006 268);
                overflow-y: auto;
            }
            [data-theme="puskesmas-dark"] .app-sidebar {
                background: oklch(14% 0.018 260);
                border-right-color: oklch(18% 0.022 260);
            }
            .main-content { margin-left: 16rem; }
        }

        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .table-wrap table { min-width: 500px; }
        .table-wrap-compact table { min-width: 400px; }
        @media (max-width: 640px) {
            .table-wrap table { min-width: 480px; }
            .page-header { flex-direction: column !important; align-items: stretch !important; }
            .page-header .header-actions { flex-wrap: wrap; }
            .page-header .header-actions > * { flex: 1; min-width: 0; }
            .btn-mobile-full { width: 100%; justify-content: center; }
            .gap-mobile { gap: 0.5rem; }
            .action-group { display: flex; flex-wrap: wrap; gap: 0.25rem; }
            .action-group > * { flex: 1; min-width: 0; }
            .action-group .btn { font-size: 0.75rem; padding: 0.375rem 0.5rem; }
            .action-group .btn .material-symbols-outlined { font-size: 1rem; }
            .btn-group { flex-wrap: wrap; }
            .btn-group > .btn, .btn-group > a, .btn-group > form, .btn-group > button {
                flex: 1; min-width: 0; justify-content: center;
            }
            main > .px-4 { padding-left: 0.75rem !important; padding-right: 0.75rem !important; }
        }
        @media (max-width: 380px) {
            .table-wrap table { min-width: 360px; }
            main > .px-4 { padding-left: 0.5rem !important; padding-right: 0.5rem !important; }
        }
        .btn-group { display: flex; align-items: center; gap: 0.75rem; }
    </style>
</head>
<body class="min-h-screen">
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>

    {{-- OVERLAY --}}
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside id="sidebar" class="app-sidebar flex flex-col">
        <div class="flex items-center gap-3 px-6 py-5">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background:oklch(42% 0.086 187);color:oklch(100% 0 0)">
                <span class="material-symbols-outlined text-3xl">medical_services</span>
            </div>
            <div>
                <h1 class="font-bold leading-tight sidebar-brand-text" style="color:oklch(42% 0.086 187);font-size:18px">Puskesmas SBD</h1>
            </div>
        </div>

        <nav class="flex-grow px-3 py-2 flex flex-col gap-0.5">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'active-icon' : '' }}">dashboard</span>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->role === 'admin')
            <div class="text-xs font-semibold uppercase tracking-wider px-4 pt-4 pb-1" style="color:oklch(20% 0.024 262 / .4)">ADMIN</div>
            <a href="{{ route('pegawai.index') }}" class="sidebar-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">badge</span>
                <span>Data Pegawai</span>
            </a>
            <a href="{{ route('pasien.index') }}" class="sidebar-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">personal_injury</span>
                <span>Data Pasien</span>
            </a>
            <a href="{{ route('obat.index') }}" class="sidebar-link {{ request()->routeIs('obat.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">medication</span>
                <span>Data Obat</span>
                @php $obatMenipisCount = \App\Models\Obat::hampirHabis()->count(); @endphp
                @if($obatMenipisCount > 0)
                <span class="ml-auto badge badge-error badge-xs">{{ $obatMenipisCount }}</span>
                @endif
            </a>
            <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">assessment</span>
                <span>Laporan</span>
            </a>
            <a href="{{ route('user.index') }}" class="sidebar-link {{ request()->routeIs('user.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">manage_accounts</span>
                <span>Manajemen User</span>
            </a>
            @endif

            @if(auth()->user()->role === 'petugas')
            <div class="text-xs font-semibold uppercase tracking-wider px-4 pt-4 pb-1" style="color:oklch(20% 0.024 262 / .4)">PETUGAS</div>
            <a href="{{ route('pasien.index') }}" class="sidebar-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">personal_injury</span>
                <span>Data Pasien</span>
            </a>
            <a href="{{ route('petugas.pendaftaran.index') }}" class="sidebar-link {{ request()->routeIs('petugas.pendaftaran.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">how_to_reg</span>
                <span>Pendaftaran</span>
            </a>
            @endif

            @if(auth()->user()->role === 'dokter')
            <div class="text-xs font-semibold uppercase tracking-wider px-4 pt-4 pb-1" style="color:oklch(20% 0.024 262 / .4)">DOKTER</div>
            <a href="{{ route('dokter.pemeriksaan.index') }}" class="sidebar-link {{ request()->routeIs('dokter.pemeriksaan.*') ? 'active' : '' }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">stethoscope</span>
                <span>Pemeriksaan</span>
            </a>
            @endif
        </nav>

        <div class="app-sidebar-footer border-t px-3 py-3 space-y-1">
            <button id="theme-toggle" class="sidebar-link w-full" style="border:none;cursor:pointer">
                <svg id="theme-icon-dark" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg id="theme-icon-light" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span id="theme-text">Dark Mode</span>
            </button>
            <a href="#" onclick="document.getElementById('modal-logout').showModal(); return false;" class="sidebar-link" style="color:oklch(56% 0.187 22)">
                <span class="material-symbols-outlined">logout</span>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-content min-h-screen flex flex-col">
        <header class="app-navbar sticky top-0 h-16 flex items-center justify-between z-20 px-4 sm:px-8" style="-webkit-backdrop-filter:blur(12px);backdrop-filter:blur(12px)">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden btn btn-ghost btn-circle btn-sm">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div>
                    <h2 class="navbar-title font-bold" style="color:oklch(12% 0.028 262)">@yield('title', 'Dashboard')</h2>
                    <p class="navbar-subtitle text-xs" style="color:oklch(20% 0.024 262 / .6)">{{ ucfirst(auth()->user()->role) }} - {{ config('app.name') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold" style="background:{{ $jam['bisa_daftar'] ? 'oklch(52% 0.1 182 / .1)' : 'oklch(72% 0.166 70 / .1)' }};color:{{ $jam['warna'] }}">
                    <span class="material-symbols-outlined text-sm">{{ $jam['icon'] }}</span>
                    {{ $jam['label'] }}
                </div>
                <form action="{{ route('dashboard.search') }}" method="GET" class="search-bar hidden md:flex items-center rounded-full px-4 py-1.5" style="background:oklch(97.5% 0.006 265)">
                    <span class="search-icon material-symbols-outlined text-sm mr-2" style="color:oklch(20% 0.024 262 / .5)">search</span>
                    <input name="q" class="bg-transparent border-none focus:outline-none text-sm w-48" style="color:oklch(12% 0.028 262)" placeholder="Cari pasien, obat..." type="text" value="{{ request('q') }}">
                </form>
                <div class="user-divider flex items-center gap-3 border-l pl-4" style="border-color:oklch(93% 0.006 268)">
                    <div class="text-right hidden sm:block">
                        <p class="user-name text-sm font-semibold" style="color:oklch(12% 0.028 262)">{{ auth()->user()->name }}</p>
                        <p class="user-role text-xs" style="color:oklch(20% 0.024 262 / .6)">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs" style="background:oklch(42% 0.086 187);color:oklch(100% 0 0)">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow px-4 sm:px-6 py-6">
            @yield('content')
        </main>
    </div>

    {{-- NOTIF: SUCCESS --}}
    @if (session('success'))
    <dialog id="notif-success" class="modal">
        <div class="modal-box text-center relative overflow-hidden">
            <div class="notif-bar notif-bar-success"></div>
            <button class="notif-close" onclick="document.getElementById('notif-success').close()">
                <span class="material-symbols-outlined">close</span>
            </button>
            <div class="notif-icon notif-icon-success">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <h3 class="text-xl font-bold mb-2" style="color:oklch(12% 0.028 262)">Berhasil!</h3>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">{{ session('success') }}</p>
            <div class="mt-6">
                <button class="notif-btn notif-btn-success" onclick="document.getElementById('notif-success').close()">Tutup</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button></button></form>
    </dialog>
    <script>
    (function(){
        var m = document.getElementById('notif-success');
        if(m){ m.showModal();
            var t = setTimeout(function(){ m.close(); }, 4000);
            m.querySelector('.notif-bar-success').style.animation = 'notif-bar-shrink 4s linear forwards';
            m.querySelector('.notif-close').onclick = function(){ clearTimeout(t); m.close(); };
            m.querySelector('.notif-btn-success').onclick = function(){ clearTimeout(t); m.close(); };
        }
    })();
    </script>
    @endif

    {{-- NOTIF: ERROR --}}
    @if (session('error'))
    <dialog id="notif-error" class="modal">
        <div class="modal-box text-center relative overflow-hidden">
            <div class="notif-bar notif-bar-error"></div>
            <button class="notif-close" onclick="document.getElementById('notif-error').close()">
                <span class="material-symbols-outlined">close</span>
            </button>
            <div class="notif-icon notif-icon-error">
                <span class="material-symbols-outlined">error</span>
            </div>
            <h3 class="text-xl font-bold mb-2" style="color:oklch(12% 0.028 262)">Gagal!</h3>
            <p class="text-sm" style="color:oklch(20% 0.024 262 / .6)">{{ session('error') }}</p>
            <div class="mt-6">
                <button class="notif-btn notif-btn-error" onclick="document.getElementById('notif-error').close()">Tutup</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button></button></form>
    </dialog>
    <script>
    (function(){
        var m = document.getElementById('notif-error');
        if(m){ m.showModal();
            var t = setTimeout(function(){ m.close(); }, 5000);
            m.querySelector('.notif-bar-error').style.animation = 'notif-bar-shrink 5s linear forwards';
            m.querySelector('.notif-close').onclick = function(){ clearTimeout(t); m.close(); };
            m.querySelector('.notif-btn-error').onclick = function(){ clearTimeout(t); m.close(); };
        }
    })();
    </script>
    @endif

    {{-- NOTIF: VALIDATION ERRORS --}}
    @if ($errors->any())
    <dialog id="notif-validation" class="modal">
        <div class="modal-box text-center relative overflow-hidden">
            <div class="notif-bar notif-bar-error"></div>
            <button class="notif-close" onclick="document.getElementById('notif-validation').close()">
                <span class="material-symbols-outlined">close</span>
            </button>
            <div class="notif-icon notif-icon-error">
                <span class="material-symbols-outlined">warning</span>
            </div>
            <h3 class="text-xl font-bold mb-2" style="color:oklch(12% 0.028 262)">Perhatian!</h3>
            <ul class="text-sm text-left max-w-xs mx-auto space-y-1" style="color:oklch(20% 0.024 262 / .6)">
                @foreach ($errors->all() as $err)
                <li class="flex items-start gap-2"><span class="material-symbols-outlined text-error" style="font-size:16px">radio_button_unchecked</span>{{ $err }}</li>
                @endforeach
            </ul>
            <div class="mt-6">
                <button class="notif-btn notif-btn-error" onclick="document.getElementById('notif-validation').close()">Tutup</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button></button></form>
    </dialog>
    <script>
    (function(){
        var m = document.getElementById('notif-validation');
        if(m){ m.showModal();
            var t = setTimeout(function(){ m.close(); }, 7000);
            m.querySelector('.notif-bar-error').style.animation = 'notif-bar-shrink 7s linear forwards';
            m.querySelector('.notif-close').onclick = function(){ clearTimeout(t); m.close(); };
        }
    })();
    </script>
    @endif

    {{-- LOGOUT MODAL --}}
    <dialog id="modal-logout" class="modal">
        <div class="modal-box text-center">
            <div class="w-20 h-20 rounded-full bg-warning/10 flex items-center justify-center mx-auto mb-5">
                <span class="material-symbols-outlined text-5xl text-warning">logout</span>
            </div>
            <h3 class="text-xl font-bold mb-2">Logout</h3>
            <p class="text-base text-base-content/70">Yakin ingin keluar dari sistem?</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-8 flex justify-center gap-3">
                @csrf
                <button type="button" class="btn-ghost-action px-6" onclick="document.getElementById('modal-logout').close()">Batal</button>
                <button class="btn-danger-action px-6">Ya, Logout</button>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button></button></form>
    </dialog>

    <script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebar-overlay').classList.toggle('open');
    }
    function closeSidebar() {
        if (window.innerWidth < 1024) {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('open');
        }
    }

    (function() {
        const html = document.documentElement;
        const toggle = document.getElementById('theme-toggle');
        const iconLight = document.getElementById('theme-icon-light');
        const iconDark = document.getElementById('theme-icon-dark');
        const text = document.getElementById('theme-text');

        function setTheme(theme) {
            html.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            const isDark = theme === 'puskesmas-dark';
            iconLight.classList.toggle('hidden', isDark);
            iconDark.classList.toggle('hidden', !isDark);
            text.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        }

        const saved = localStorage.getItem('theme') || 'puskesmas';
        setTheme(saved);

        toggle.addEventListener('click', function() {
            const current = html.getAttribute('data-theme');
            setTheme(current === 'puskesmas-dark' ? 'puskesmas' : 'puskesmas-dark');
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
