<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="puskesmas">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        h1, h2, h3, h4 { font-family: 'Manrope', system-ui, sans-serif; }

    </style>
</head>
<body class="bg-[#f8f9ff] min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm mx-4">
        <div class="bg-white rounded-3xl shadow-soft border border-[#00685f]/5 p-8">
            <div class="flex justify-center mb-4">
                <div class="w-14 h-14 bg-[#00685f]/10 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#00685f]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M6 21V7a2 2 0 012-2h8a2 2 0 012 2v14"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v4M10 9h4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 21v-4a2 2 0 012-2h2a2 2 0 012 2v4"/>
                    </svg>
                </div>
            </div>
            <h2 class="font-heading text-2xl font-extrabold text-center text-[#0d1c2f]">Login</h2>
            <p class="font-body text-sm text-[#0d1c2f]/50 text-center mb-6">Masuk ke {{ config('app.name') }}</p>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="font-body text-sm font-semibold text-[#0d1c2f]/70 mb-1.5 block">Email</label>
                    <input type="email" name="email" class="w-full font-body text-sm bg-[#f8f9ff] border border-[#00685f]/10 rounded-2xl px-4 py-3.5 text-[#0d1c2f] focus:outline-none focus:ring-2 focus:ring-[#00685f]/20 focus:border-[#00685f]/40 transition-all placeholder:text-[#0d1c2f]/30 @error('email') border-error @enderror" value="{{ old('email') }}" required placeholder="admin@puskesmas.test">
                    @error('email') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="font-body text-sm font-semibold text-[#0d1c2f]/70 mb-1.5 block">Password</label>
                    <input type="password" name="password" class="w-full font-body text-sm bg-[#f8f9ff] border border-[#00685f]/10 rounded-2xl px-4 py-3.5 text-[#0d1c2f] focus:outline-none focus:ring-2 focus:ring-[#00685f]/20 focus:border-[#00685f]/40 transition-all" required placeholder="password">
                </div>
                <button type="submit" class="w-full bg-[#00685f] text-white font-body font-semibold text-base py-3.5 rounded-2xl hover:bg-[#005a52] transition-all duration-300 shadow-lg shadow-[#00685f]/20 hover:shadow-[#00685f]/30 mt-2">Login</button>
            </form>
            <div class="text-center mt-6">
                <a href="{{ route('landing') }}" class="font-body text-sm font-semibold text-[#00685f] hover:text-[#005a52] transition-colors">Kembali ke Beranda</a>
            </div>
        </div>
    </div>

</body>
</html>
