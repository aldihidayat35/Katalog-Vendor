<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 border border-stone-200/80 shadow-subtle">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-stone-100">
                    <div>
                        <span class="text-xs font-semibold uppercase tracking-widest text-champagne-600">Portal Member</span>
                        <h1 class="text-3xl font-serif font-bold text-charcoal-900 mt-1">Halo, {{ auth()->user()->name }}</h1>
                        <p class="text-sm text-stone-500">Selamat datang di akun katalog undangan pernikahan Anda.</p>
                    </div>
                    @if(auth()->user()->isAdmin())
                        <x-button href="{{ route('admin.dashboard') }}" variant="primary" size="md">
                            Buka Admin Panel &rarr;
                        </x-button>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
                    <div class="p-6 rounded-2xl bg-ivory-100 border border-champagne-200">
                        <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Email Terdaftar</span>
                        <p class="text-base font-semibold text-charcoal-900 mt-1">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-ivory-100 border border-champagne-200">
                        <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">WhatsApp / Telepon</span>
                        <p class="text-base font-semibold text-charcoal-900 mt-1">{{ auth()->user()->phone ?? 'Belum diisi' }}</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-ivory-100 border border-champagne-200">
                        <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Status Akun</span>
                        <p class="text-base font-semibold text-charcoal-900 mt-1">
                            <x-badge :color="auth()->user()->isAdmin() ? 'gold' : 'green'">
                                {{ auth()->user()->role === 'admin' ? 'Administrator' : 'Member Aktif' }}
                            </x-badge>
                        </p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-stone-100 flex flex-wrap gap-4">
                    <x-button href="{{ url('/catalog') }}" variant="secondary" size="md">
                        Jelajahi Katalog
                    </x-button>
                    <x-button href="{{ route('profile.edit') }}" variant="outline" size="md">
                        Edit Profil
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
