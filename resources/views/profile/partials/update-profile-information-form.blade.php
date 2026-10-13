<section>
    <header>
        <h2 class="text-lg font-bold font-serif text-charcoal-900">
            Informasi Profil &amp; Data Pemesan
        </h2>

        <p class="mt-1 text-xs text-stone-500">
            Data ini akan digunakan secara otomatis saat Anda melakukan checkout pemesanan undangan.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <!-- Avatar & Account Status Card -->
        <div class="flex items-center gap-4 p-4 rounded-2xl bg-stone-50 border border-stone-200/80">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover shadow-sm ring-2 ring-champagne/40">
            @else
                <div class="w-16 h-16 rounded-full bg-champagne-100 text-champagne-800 flex items-center justify-center font-bold text-xl ring-2 ring-champagne/40">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h3 class="text-sm font-bold text-charcoal-900">{{ $user->name }}</h3>
                <p class="text-xs text-stone-500 font-medium">
                    {{ $user->username ? '@' . $user->username : 'Belum diatur' }}
                </p>
                @if($user->google_id && config('services.google.enabled', false))
                    <span class="inline-flex items-center gap-1.5 mt-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <svg class="w-3 h-3" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17Z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.27 21.43 7.35 24 12 24Z"/><path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15Z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.35 0 3.27 2.57 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98Z"/></svg>
                        Terhubung dengan Akun Google
                    </span>
                @endif
            </div>
        </div>

        <div>
            <label for="name" class="block text-xs font-bold text-charcoal-700 mb-1">
                Nama Lengkap <span class="text-rose-500">*</span>
            </label>
            <input
                id="name"
                name="name"
                type="text"
                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                value="{{ old('name', $user->name) }}"
                required
                autocomplete="name"
            />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="username" class="block text-xs font-bold text-charcoal-700 mb-1">
                Username
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-400 text-sm">@</span>
                <input
                    id="username"
                    name="username"
                    type="text"
                    placeholder="username"
                    class="w-full pl-8 rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                    value="{{ old('username', $user->username) }}"
                />
            </div>
            <span class="text-[10px] text-stone-400 mt-0.5 block">Digunakan sebagai identitas unik akun Anda.</span>
            <x-input-error class="mt-1" :messages="$errors->get('username')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-charcoal-700 mb-1">
                Alamat Email <span class="text-rose-500">*</span>
            </label>
            <input
                id="email"
                name="email"
                type="email"
                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs mt-2 text-stone-700">
                        Alamat email Anda belum terverifikasi.
                        <button form="send-verification" class="underline text-xs text-champagne-700 hover:text-champagne-900 font-semibold">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600">
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- No WhatsApp / HP -->
        <div>
            <label for="phone" class="block text-xs font-bold text-charcoal-700 mb-1">
                Nomor WhatsApp / HP
            </label>
            <input
                id="phone"
                name="phone"
                type="text"
                placeholder="Contoh: 081234567890"
                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                value="{{ old('phone', $user->phone) }}"
            />
            <span class="text-[10px] text-stone-400 mt-0.5 block">Format: 08xxxxxxxxxx atau 62xxxxxxxxxx</span>
            <x-input-error class="mt-1" :messages="$errors->get('phone')" />
        </div>

        <!-- Akun Instagram -->
        <div>
            <label for="instagram" class="block text-xs font-bold text-charcoal-700 mb-1">
                Akun Instagram
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-stone-400 text-sm">@</span>
                <input
                    id="instagram"
                    name="instagram"
                    type="text"
                    placeholder="username_kamu"
                    class="w-full pl-8 rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne"
                    value="{{ old('instagram', $user->instagram ? ltrim($user->instagram, '@') : '') }}"
                />
            </div>
            <x-input-error class="mt-1" :messages="$errors->get('instagram')" />
        </div>

        <!-- Alamat Pengiriman Default -->
        <div>
            <label for="address" class="block text-xs font-bold text-charcoal-700 mb-1">
                Alamat Pengiriman Default
            </label>
            <textarea
                id="address"
                name="address"
                rows="3"
                placeholder="Alamat jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, kode pos"
                class="w-full rounded-xl text-sm border-stone-300 focus:border-champagne focus:ring-champagne leading-relaxed"
            >{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-1" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button
                type="submit"
                class="px-6 py-3 rounded-full bg-champagne hover:bg-champagne-600 text-white font-semibold text-xs shadow-goldglow transition-colors"
            >
                Simpan Profil
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs text-emerald-700 font-semibold"
                >
                    Profil berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
