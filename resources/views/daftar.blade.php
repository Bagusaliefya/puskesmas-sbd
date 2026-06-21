<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="puskesmas">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Pasien</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,0&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        h1, h2, h3, h4 { font-family: 'Manrope', system-ui, sans-serif; }
        .glass { background: rgba(255,255,255,0.75); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        .glass-scrolled { background: rgba(255,255,255,0.9); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-bottom: 1px solid rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-[#f8f9ff] min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 glass transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">
                <a href="{{ route('landing') }}" class="flex items-center gap-2 no-underline">
                    <span class="material-symbols-outlined text-[#00685f] text-3xl">local_hospital</span>
                    <span class="font-heading text-xl lg:text-2xl font-extrabold text-[#00685f] tracking-tight">{{ config('app.name') }}</span>
                </a>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold" style="background:{{ $status['bisa_daftar'] ? 'rgba(0,104,95,0.08)' : 'rgba(221,140,30,0.1)' }};color:{{ $status['warna'] ?? ($status['bisa_daftar'] ? '#00685f' : '#dd8c1e') }}">
                        <span class="material-symbols-outlined text-sm">{{ $status['icon'] }}</span>
                        {{ $status['label'] }}
                    </span>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 font-body text-sm font-semibold text-[#0d1c2f]/60 hover:text-[#00685f] px-4 py-2 transition-colors">
                        <span class="material-symbols-outlined text-base">login</span>
                        Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex-1 flex items-start justify-center px-4 pt-24 sm:pt-28 lg:pt-36 pb-8">
        <div class="w-full max-w-3xl">
            <div class="mb-8">
                <span class="inline-block font-body text-sm font-semibold text-[#00685f] bg-[#00685f]/10 px-4 py-1.5 rounded-full mb-3">Pendaftaran Pasien</span>
                <h1 class="font-heading text-3xl font-extrabold text-[#0d1c2f]">Daftar Berobat</h1>
                <p class="font-body text-sm text-[#0d1c2f]/60 mt-1">Isi data diri Anda untuk mendaftar berobat di Puskesmas.</p>
            </div>

            <div class="bg-white rounded-3xl shadow-soft border border-[#00685f]/5 p-6 md:p-8">
                @if(!$status['bisa_daftar'])
                <div class="flex items-center gap-3 p-4 rounded-2xl mb-5 text-sm font-semibold" style="background:{{ $status['status'] === 'tutup' ? 'rgba(221,140,30,0.1)' : 'rgba(0,104,95,0.08)' }};color:{{ $status['warna'] ?? '#dd8c1e' }}">
                    <span class="material-symbols-outlined">{{ $status['icon'] }}</span>
                    {{ $status['pesan'] }}
                </div>
                @endif
                <form method="POST" action="{{ route('daftar.submit') }}" id="form-daftar" class="space-y-5">
                    @csrf

                    <div>
                        <label class="font-body text-sm font-semibold text-[#0d1c2f]/70 mb-1.5 block">Nama Lengkap <span class="text-error">*</span></label>
                        <input type="text" name="nama_pasien" class="w-full font-body text-sm bg-[#f8f9ff] border border-[#00685f]/10 rounded-2xl px-4 py-3.5 text-[#0d1c2f] focus:outline-none focus:ring-2 focus:ring-[#00685f]/20 focus:border-[#00685f]/40 transition-all placeholder:text-[#0d1c2f]/30 @error('nama_pasien') border-error @enderror" value="{{ old('nama_pasien') }}" required placeholder="Masukkan nama lengkap" pattern="[A-Za-z\s\.\']+" title="Hanya huruf yang diperbolehkan" oninput="this.value = this.value.replace(/[^A-Za-z\s\.\']/g, '')">
                        @error('nama_pasien') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-body text-sm font-semibold text-[#0d1c2f]/70 mb-1.5 block">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="w-full font-body text-sm bg-[#f8f9ff] border border-[#00685f]/10 rounded-2xl px-4 py-3.5 text-[#0d1c2f] focus:outline-none focus:ring-2 focus:ring-[#00685f]/20 focus:border-[#00685f]/40 transition-all" value="{{ old('tanggal_lahir') }}">
                        </div>
                        <div>
                            <label class="font-body text-sm font-semibold text-[#0d1c2f]/70 mb-1.5 block">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full font-body text-sm bg-[#f8f9ff] border border-[#00685f]/10 rounded-2xl px-4 py-3.5 text-[#0d1c2f] focus:outline-none focus:ring-2 focus:ring-[#00685f]/20 focus:border-[#00685f]/40 transition-all appearance-none">
                                <option value="">Pilih</option>
                                <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                                <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-body text-sm font-semibold text-[#0d1c2f]/70 mb-1.5 block">No. HP</label>
                            <input type="tel" name="no_hp" inputmode="numeric" pattern="[0-9+\-\s]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full font-body text-sm bg-[#f8f9ff] border border-[#00685f]/10 rounded-2xl px-4 py-3.5 text-[#0d1c2f] focus:outline-none focus:ring-2 focus:ring-[#00685f]/20 focus:border-[#00685f]/40 transition-all placeholder:text-[#0d1c2f]/30" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" title="Hanya angka yang diperbolehkan">
                        </div>
                        <div>
                            <label class="font-body text-sm font-semibold text-[#0d1c2f]/70 mb-1.5 block">Golongan Darah</label>
                            <select name="golongan_darah" class="w-full font-body text-sm bg-[#f8f9ff] border border-[#00685f]/10 rounded-2xl px-4 py-3.5 text-[#0d1c2f] focus:outline-none focus:ring-2 focus:ring-[#00685f]/20 focus:border-[#00685f]/40 transition-all appearance-none">
                                <option value="">Pilih</option>
                                <option value="A" @selected(old('golongan_darah') === 'A')>A</option>
                                <option value="B" @selected(old('golongan_darah') === 'B')>B</option>
                                <option value="AB" @selected(old('golongan_darah') === 'AB')>AB</option>
                                <option value="O" @selected(old('golongan_darah') === 'O')>O</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="font-body text-sm font-semibold text-[#0d1c2f]/70 mb-1.5 block">Alamat</label>
                        <textarea name="alamat" class="w-full font-body text-sm bg-[#f8f9ff] border border-[#00685f]/10 rounded-2xl px-4 py-3.5 text-[#0d1c2f] focus:outline-none focus:ring-2 focus:ring-[#00685f]/20 focus:border-[#00685f]/40 transition-all placeholder:text-[#0d1c2f]/30" rows="2" placeholder="Masukkan alamat"></textarea>
                    </div>

                    <div>
                        <label class="font-body text-sm font-semibold text-[#0d1c2f]/70 mb-1.5 block">Keluhan <span class="text-error">*</span></label>
                        <textarea name="keluhan" class="w-full font-body text-sm bg-[#f8f9ff] border border-[#00685f]/10 rounded-2xl px-4 py-3.5 text-[#0d1c2f] focus:outline-none focus:ring-2 focus:ring-[#00685f]/20 focus:border-[#00685f]/40 transition-all placeholder:text-[#0d1c2f]/30 @error('keluhan') border-error @enderror" rows="3" placeholder="Jelaskan keluhan yang Anda rasakan...">{{ old('keluhan') }}</textarea>
                        @error('keluhan') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button type="submit" class="btn-primary-action flex-1 justify-center py-3.5 text-base disabled:opacity-50" id="btn-daftar">Daftar Sekarang</button>
                        <a href="{{ route('landing') }}" class="btn-ghost-action flex-1 justify-center py-3.5 text-base no-underline">Batal</a>
                    </div>
                </form>
                <script>
                    document.getElementById('form-daftar')?.addEventListener('submit', function() {
                        document.getElementById('btn-daftar').disabled = true;
                    });
                </script>
            </div>
        </div>
    </div>

    <div class="py-5 text-center">
        <p class="font-body text-xs text-[#0d1c2f]/30">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>

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

    </script>
</html>
