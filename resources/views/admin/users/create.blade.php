<x-admin-layout>
    <x-slot:heading>Tambah Pengguna Baru</x-slot:heading>

    <x-admin.page-header
        title="Tambah Akun Pengguna Baru"
        subtitle="Buat akun administrator staf baru atau daftarkan akun pelanggan manual ke sistem."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.users.index') }}" variant="outline" size="md">
                &larr; Kembali ke Daftar
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="max-w-3xl bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle">
        <form
            method="POST"
            action="{{ route('admin.users.store') }}"
            x-data="{
                role: '{{ old('role', 'member') }}',
            }"
            class="space-y-6"
        >
            @csrf

            <!-- Role Selection (Radio Cards) -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                    Tipe Hak Akses (Role) <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label
                        class="p-4 rounded-2xl border cursor-pointer transition-all flex items-start gap-3"
                        :class="role === 'member' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-stone-200 hover:border-stone-300'"
                    >
                        <input type="radio" name="role" value="member" x-model="role" class="mt-0.5 text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <span class="block text-sm font-bold text-charcoal-900">🛍️ Member (Pelanggan)</span>
                            <span class="text-xs text-stone-500 leading-relaxed block mt-0.5">
                                Akun pemesan biasa. Dapat memesan undangan, melihat keranjang, dan mengecek riwayat pesanan.
                            </span>
                        </div>
                    </label>

                    <label
                        class="p-4 rounded-2xl border cursor-pointer transition-all flex items-start gap-3"
                        :class="role === 'admin' ? 'border-champagne-600 bg-champagne-50/40 ring-2 ring-champagne-500/20' : 'border-stone-200 hover:border-stone-300'"
                    >
                        <input type="radio" name="role" value="admin" x-model="role" class="mt-0.5 text-champagne-600 focus:ring-champagne-500">
                        <div>
                            <span class="block text-sm font-bold text-charcoal-900">👑 Administrator (Staf)</span>
                            <span class="text-xs text-stone-500 leading-relaxed block mt-0.5">
                                Akses penuh ke dashboard admin, pengelolaan produk, katalog, pesanan, dan konfigurasi website.
                            </span>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2 border-t border-stone-100">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="Contoh: Rian Pratama"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="rian@example.com"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Username -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Username (Opsional)
                    </label>
                    <input
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="rian_pratama"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 font-mono"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">
                        Kosongkan jika ingin sistem membuat username otomatis dari email.
                    </span>
                    @error('username')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Password Masuk <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        minlength="8"
                        placeholder="Minimal 8 karakter"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">
                        Gunakan kombinasi password yang kuat untuk keamanan akun.
                    </span>
                    @error('password')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Phone -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        No. WhatsApp / HP
                    </label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Contoh: 081234567890"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 font-mono"
                    >
                    @error('phone')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Akun Instagram
                    </label>
                    <input
                        type="text"
                        name="instagram"
                        value="{{ old('instagram') }}"
                        placeholder="@username"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 font-mono"
                    >
                    @error('instagram')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                    Alamat Pengiriman Default
                </label>
                <textarea
                    name="address"
                    rows="3"
                    placeholder="Alamat jalan, nomor rumah, kecamatan, kota/kabupaten..."
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 leading-relaxed"
                >{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status Checkbox -->
            <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200">
                <label class="inline-flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-stone-300"
                    >
                    <div>
                        <span class="text-sm font-semibold text-charcoal-900 block">Akun Aktif</span>
                        <span class="text-xs text-stone-500 block">
                            Pengguna dapat langsung login dan menggunakan sistem setelah akun dibuat.
                        </span>
                    </div>
                </label>
            </div>

            <!-- Submit Controls -->
            <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                <a
                    href="{{ route('admin.users.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-stone-300 text-sm font-semibold text-stone-600 hover:bg-stone-50 transition-colors"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="px-6 py-2.5 rounded-xl bg-charcoal-900 text-white text-sm font-semibold hover:bg-black transition-colors shadow-sm"
                >
                    Simpan Pengguna Baru
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
