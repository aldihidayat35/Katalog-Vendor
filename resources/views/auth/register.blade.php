<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-serif font-bold text-charcoal-900">Daftar Akun Member</h2>
        <p class="text-xs text-stone-500 mt-1">Dapatkan kemudahan checkout cepat dan konsultasi terpadu.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <x-form.input
            label="Nama Lengkap"
            id="name"
            name="name"
            :value="old('name')"
            required
            autofocus
            autocomplete="name"
            placeholder="Nama Anda"
        />

        <!-- Email Address -->
        <x-form.input
            label="Alamat Email"
            id="email"
            type="email"
            name="email"
            :value="old('email')"
            required
            autocomplete="username"
            placeholder="nama@email.com"
        />

        <!-- Phone / WhatsApp Number (Required in PRD & Prompt) -->
        <x-form.input
            label="Nomor WhatsApp / Telepon"
            id="phone"
            type="tel"
            name="phone"
            :value="old('phone')"
            placeholder="Contoh: 081234567890"
            hint="Digunakan untuk mempermudah format chat order WhatsApp."
        />

        <!-- Password -->
        <x-form.input
            label="Kata Sandi"
            id="password"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            placeholder="Minimal 8 karakter"
        />

        <!-- Confirm Password -->
        <x-form.input
            label="Konfirmasi Kata Sandi"
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            placeholder="Ulangi kata sandi"
        />

        <div class="pt-2">
            <x-button type="submit" variant="primary" size="lg" class="w-full">
                Daftar Sebagai Member
            </x-button>
        </div>

        <div class="text-center pt-3 border-t border-stone-100">
            <p class="text-xs text-stone-600">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-semibold text-champagne-700 hover:text-champagne-800">
                    Masuk di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
