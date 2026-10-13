<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-serif font-bold text-charcoal-900">Selamat Datang Kembali</h2>
        <p class="text-xs text-stone-500 mt-1">Masuk untuk melihat pesanan dan melanjutkan belanja.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('error'))
        <div class="mb-5 p-3.5 rounded-xl text-xs font-medium text-rose-700 bg-rose-50 border border-rose-200 flex items-start gap-2.5">
            <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <x-form.input
            label="Alamat Email"
            id="email"
            type="email"
            name="email"
            :value="old('email')"
            required
            autofocus
            autocomplete="username"
            placeholder="nama@email.com"
        />

        <!-- Password -->
        <x-form.input
            label="Kata Sandi"
            id="password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            placeholder="••••••••"
        />

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-stone-300 text-champagne focus:ring-champagne" name="remember">
                <span class="ms-2 text-stone-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-champagne-700 hover:text-champagne-800 font-medium transition-colors" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-button type="submit" variant="primary" size="lg" class="w-full">
                Masuk Sekarang
            </x-button>
        </div>

        @if (config('services.google.enabled', false))
            <!-- Divider -->
            <div class="relative my-3">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-stone-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white px-3 text-stone-500 font-medium">atau masuk dengan</span>
                </div>
            </div>

            <!-- Google OAuth Button -->
            <a href="{{ route('auth.google.redirect') }}" class="w-full flex items-center justify-center gap-3 py-2.5 px-4 rounded-xl border border-stone-300 bg-white hover:bg-stone-50 text-stone-700 text-sm font-semibold shadow-xs transition-all hover:border-stone-400 focus:outline-none focus:ring-2 focus:ring-champagne/40">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17Z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.27 21.43 7.35 24 12 24Z"/>
                    <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15Z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.35 0 3.27 2.57 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98Z"/>
                </svg>
                <span>Lanjutkan dengan Google</span>
            </a>
        @endif

        <div class="text-center pt-3 border-t border-stone-100">
            <p class="text-xs text-stone-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-champagne-700 hover:text-champagne-800">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
