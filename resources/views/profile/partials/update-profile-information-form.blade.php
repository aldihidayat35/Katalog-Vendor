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
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
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
