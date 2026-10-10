<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-serif font-bold text-charcoal-900">Selamat Datang Kembali</h2>
        <p class="text-xs text-stone-500 mt-1">Masuk untuk melihat pesanan dan melanjutkan belanja.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

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
