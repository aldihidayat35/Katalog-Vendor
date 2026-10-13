<x-admin-layout>
    <x-slot:heading>Edit Pengguna</x-slot:heading>

    <x-admin.page-header
        title="Edit Data Pengguna"
        subtitle="Perbarui data profil, ubah hak akses (role), atur password baru, atau sesuaikan status akun."
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
            action="{{ route('admin.users.update', $user) }}"
            x-data="{
                role: '{{ old('role', $user->role) }}',
            }"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <!-- Warning if editing currently logged-in account -->
            @if($user->id === auth()->id())
                <div class="p-4 rounded-2xl bg-champagne-50 border border-champagne-300 text-xs text-champagne-900 flex items-start gap-3">
                    <svg class="w-5 h-5 text-champagne-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <strong class="font-bold block">Ini adalah akun Anda sendiri yang sedang aktif.</strong>
                        <span>Demi keamanan sistem, Anda tidak dapat menurunkan hak akses Administrator atau menonaktifkan akun Anda sendiri.</span>
                    </div>
                </div>
            @endif

            <!-- Role Selection (Radio Cards) -->
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                    Tipe Hak Akses (Role) <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label
                        class="p-4 rounded-2xl border cursor-pointer transition-all flex items-start gap-3 {{ $user->id === auth()->id() ? 'opacity-60 cursor-not-allowed' : '' }}"
                        :class="role === 'member' ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-stone-200 hover:border-stone-300'"
                    >
                        <input
                            type="radio"
                            name="role"
                            value="member"
                            x-model="role"
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}
                            class="mt-0.5 text-emerald-600 focus:ring-emerald-500"
                        >
                        <div>
                            <span class="block text-sm font-bold text-charcoal-900">🛍️ Member (Pelanggan)</span>
                            <span class="text-xs text-stone-500 leading-relaxed block mt-0.5">
                                Akun pemesan biasa. Dapat memesan undangan dan mengecek riwayat pesanan.
                            </span>
                        </div>
                    </label>

                    <label
                        class="p-4 rounded-2xl border cursor-pointer transition-all flex items-start gap-3"
                        :class="role === 'admin' ? 'border-champagne-600 bg-champagne-50/40 ring-2 ring-champagne-500/20' : 'border-stone-200 hover:border-stone-300'"
                    >
                        <input
                            type="radio"
                            name="role"
                            value="admin"
                            x-model="role"
                            class="mt-0.5 text-champagne-600 focus:ring-champagne-500"
                        >
                        <div>
                            <span class="block text-sm font-bold text-charcoal-900">👑 Administrator (Staf)</span>
                            <span class="text-xs text-stone-500 leading-relaxed block mt-0.5">
                                Akses penuh ke dashboard admin, pengelolaan produk, katalog, pesanan, dan konfigurasi website.
                            </span>
                        </div>
                    </label>
                </div>
                @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="admin">
                @endif
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
                        value="{{ old('name', $user->name) }}"
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
                        value="{{ old('email', $user->email) }}"
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
                        Username
                    </label>
                    <input
                        type="text"
                        name="username"
                        value="{{ old('username', $user->username) }}"
                        placeholder="rian_pratama"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 font-mono"
                    >
                    @error('username')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (Optional on update) -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        Password Baru (Opsional)
                    </label>
                    <input
                        type="password"
                        name="password"
                        minlength="8"
                        placeholder="Kosongkan jika tidak ingin mengubah password"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">
                        Isi kolom ini hanya jika Anda ingin mereset password pengguna ini.
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
                        value="{{ old('phone', $user->phone) }}"
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
                        value="{{ old('instagram', $user->instagram) }}"
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
                >{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status Checkbox -->
            <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200">
                <label class="inline-flex items-center gap-3 {{ $user->id === auth()->id() ? 'opacity-60 cursor-not-allowed' : 'cursor-pointer' }}">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                        {{ $user->id === auth()->id() ? 'disabled' : '' }}
                        class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-stone-300"
                    >
                    <div>
                        <span class="text-sm font-semibold text-charcoal-900 block">Akun Aktif</span>
                        <span class="text-xs text-stone-500 block">
                            Jika dinonaktifkan (unchecked), pengguna tidak akan dapat login ke dalam website.
                        </span>
                    </div>
                </label>
                @if($user->id === auth()->id())
                    <input type="hidden" name="is_active" value="1">
                @endif
            </div>

            <!-- Submit Controls -->
            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                <div>
                    @if($user->id !== auth()->id() && $user->orders()->doesntExist())
                        <button
                            type="button"
                            onclick="if(confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} secara permanen?')) { document.getElementById('delete-user-form').submit(); }"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 underline"
                        >
                            Hapus Akun Pengguna Ini
                        </button>
                    @endif
                </div>

                <div class="flex items-center gap-3">
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
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

        @if($user->id !== auth()->id() && $user->orders()->doesntExist())
            <form id="delete-user-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</x-admin-layout>
