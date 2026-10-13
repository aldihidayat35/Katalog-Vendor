<x-admin-layout>
    <x-slot:heading>Detail Pengguna: {{ $user->name }}</x-slot:heading>

    <x-admin.page-header
        title="{{ $user->name }}"
        subtitle="Informasi profil akun, hak akses sistem, dan riwayat aktivitas transaksi pemesanan."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.users.index') }}" variant="outline" size="md">
                &larr; Kembali
            </x-button>

            <x-button href="{{ route('admin.users.edit', $user) }}" variant="primary" size="md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Akun
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    @if($user->isSuspended())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-900 mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <div>
                <strong class="font-bold block text-sm">Akun Ini Sedang Dinonaktifkan (Suspended)</strong>
                <span>Pengguna dengan akun ini tidak dapat login ke website publik hingga statusnya diaktifkan kembali.</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- 1. Profile Info Card -->
        <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-subtle space-y-6 lg:col-span-1">
            <div class="flex items-center gap-4">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-stone-200">
                @else
                    <div class="w-16 h-16 rounded-full {{ $user->isAdmin() ? 'bg-champagne-100 text-champagne-800' : 'bg-stone-100 text-stone-700' }} flex items-center justify-center font-bold text-2xl border border-stone-200 shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <h3 class="font-serif font-bold text-lg text-charcoal-900 truncate">{{ $user->name }}</h3>
                    <p class="text-xs text-stone-500 truncate">{{ $user->email }}</p>
                    @if($user->username)
                        <p class="text-xs font-mono text-champagne-700 mt-0.5">&#64;{{ $user->username }}</p>
                    @endif
                </div>
            </div>

            <!-- Badges -->
            <div class="flex items-center gap-2 pt-4 border-t border-stone-100">
                @if ($user->isAdmin())
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-champagne-50 text-champagne-800 border border-champagne-300">
                        👑 Administrator
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                        🛍️ Member
                    </span>
                @endif

                @if ($user->isActive())
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                        Aktif
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                        Suspended
                    </span>
                @endif
            </div>

            <!-- Details List -->
            <div class="space-y-3 pt-4 border-t border-stone-100 text-xs">
                <div>
                    <span class="text-stone-400 uppercase tracking-wider block text-[10px]">No. WhatsApp / HP</span>
                    <span class="font-semibold text-charcoal-900 font-mono mt-0.5 block">
                        {{ $user->phone ?? '-' }}
                    </span>
                </div>

                <div>
                    <span class="text-stone-400 uppercase tracking-wider block text-[10px]">Akun Instagram</span>
                    <span class="font-semibold text-charcoal-900 font-mono mt-0.5 block">
                        {{ $user->instagram ? (str_starts_with($user->instagram, '@') ? $user->instagram : '@'.$user->instagram) : '-' }}
                    </span>
                </div>

                <div>
                    <span class="text-stone-400 uppercase tracking-wider block text-[10px]">Alamat Default</span>
                    <p class="font-semibold text-charcoal-900 leading-relaxed mt-0.5">
                        {{ $user->address ?? '-' }}
                    </p>
                </div>

                <div>
                    <span class="text-stone-400 uppercase tracking-wider block text-[10px]">Terdaftar Pada</span>
                    <span class="font-medium text-stone-600 mt-0.5 block">
                        {{ $user->created_at->translatedFormat('d F Y, H:i') }}
                    </span>
                </div>
            </div>

            <!-- Quick Status Toggle Action -->
            @if($user->id !== auth()->id())
                <div class="pt-4 border-t border-stone-100">
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                        @csrf
                        @method('PATCH')
                        <button
                            type="submit"
                            class="w-full py-2 px-4 rounded-xl text-xs font-semibold transition-colors {{ $user->isActive() ? 'border border-rose-300 text-rose-700 hover:bg-rose-50' : 'border border-emerald-300 text-emerald-700 hover:bg-emerald-50' }}"
                        >
                            {{ $user->isActive() ? '🛑 Nonaktifkan Akun Pengguna' : '✅ Aktifkan Kembali Akun' }}
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- 2. Order History (Right 2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle">
                <div class="flex items-center justify-between border-b border-stone-100 pb-3 mb-4">
                    <div>
                        <h3 class="font-serif font-bold text-lg text-charcoal-900">Riwayat Pesanan Pelanggan</h3>
                        <p class="text-xs text-stone-500">Daftar transaksi undangan yang dibuat oleh akun ini</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-stone-100 text-charcoal-800">
                        Total: {{ $orders->total() }}
                    </span>
                </div>

                @if($orders->count() > 0)
                    <div class="divide-y divide-stone-100">
                        @foreach($orders as $order)
                            <div class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="font-mono font-bold text-sm text-champagne-700 hover:underline">
                                            {{ $order->order_number }}
                                        </a>
                                        <x-admin.status-badge :status="$order->status" type="order" />
                                    </div>
                                    <p class="text-xs text-stone-500 mt-1">
                                        {{ $order->created_at->translatedFormat('d F Y, H:i') }} &bull; Skema: {{ strtoupper($order->payment_option) }}
                                    </p>
                                </div>

                                <div class="text-right sm:shrink-0 flex items-center gap-3">
                                    <div>
                                        <span class="text-xs text-stone-400 block uppercase tracking-wider">Total</span>
                                        <span class="font-serif font-bold text-charcoal-900 text-sm">
                                            {{ rupiah($order->total_price) }}
                                        </span>
                                    </div>
                                    <a
                                        href="{{ route('admin.orders.show', $order) }}"
                                        class="px-3 py-1.5 rounded-xl border border-stone-200 text-xs font-semibold text-stone-700 hover:bg-stone-50"
                                    >
                                        Rincian &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-stone-100">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-10 text-stone-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <p class="text-xs">Belum ada riwayat pesanan yang dibuat oleh akun ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
