<section>
    <header>
        <h2 class="text-lg font-bold font-serif text-charcoal-900">
            Perbarui Kata Sandi
        </h2>

        <p class="mt-1 text-xs text-stone-500">
            Pastikan akun Anda menggunakan kata sandi yang aman dan tidak digunakan di situs lain.
        </p>
    </header>

    <form method="post" action="{{ route('profile.password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-charcoal-700 mb-1">
                Kata Sandi Saat Ini
            </label>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('current_password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-charcoal-700 mb-1">
                Kata Sandi Baru
            </label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-charcoal-700 mb-1">
                Konfirmasi Kata Sandi Baru
            </label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button
                type="submit"
                class="px-6 py-3 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-xs shadow-goldglow transition-colors"
            >
                Simpan Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs text-emerald-700 font-semibold"
                >
                    Kata sandi berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
