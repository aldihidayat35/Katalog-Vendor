<x-admin-layout>
    <x-slot:heading>Manajemen Pengguna</x-slot:heading>

    <x-admin.page-header
        title="Manajemen Pengguna & Member"
        subtitle="Kelola seluruh akun pengguna sistem, tambahkan staf administrator baru, atur role, reset password, dan pantau status akun."
    >
        <x-slot:actions>
            <x-button
                href="{{ route('admin.users.export', request()->query()) }}"
                variant="outline"
                size="md"
            >
                <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </x-button>

            <x-button
                href="{{ route('admin.users.create') }}"
                variant="primary"
                size="md"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                + Tambah Pengguna Baru
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Quick Stats Overview -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-stone-200/80 shadow-subtle">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Total Pengguna</span>
            <span class="text-2xl font-serif font-bold text-charcoal-900 mt-1 block">{{ number_format($totalCount) }}</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-stone-200/80 shadow-subtle">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Administrator</span>
            <span class="text-2xl font-serif font-bold text-champagne-700 mt-1 block">{{ number_format($adminCount) }}</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-stone-200/80 shadow-subtle">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Member Terdaftar</span>
            <span class="text-2xl font-serif font-bold text-emerald-700 mt-1 block">{{ number_format($memberCount) }}</span>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-stone-200/80 shadow-subtle">
            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Nonaktif / Suspended</span>
            <span class="text-2xl font-serif font-bold text-rose-600 mt-1 block">{{ number_format($suspendedCount) }}</span>
        </div>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-subtle mb-6 space-y-4">
        <!-- Role Tabs -->
        <div class="flex items-center gap-2 border-b border-stone-100 pb-3 overflow-x-auto">
            <a
                href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'all'])) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ ($roleFilter === 'all') ? 'bg-charcoal-900 text-white shadow-xs' : 'text-stone-600 hover:bg-stone-100' }}"
            >
                Semua Pengguna ({{ $totalCount }})
            </a>
            <a
                href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'admin'])) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ ($roleFilter === 'admin') ? 'bg-champagne-700 text-white shadow-xs' : 'text-stone-600 hover:bg-stone-100' }}"
            >
                👑 Administrator ({{ $adminCount }})
            </a>
            <a
                href="{{ route('admin.users.index', array_merge(request()->except('role', 'page'), ['role' => 'member'])) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ ($roleFilter === 'member') ? 'bg-emerald-700 text-white shadow-xs' : 'text-stone-600 hover:bg-stone-100' }}"
            >
                🛍️ Member / Pelanggan ({{ $memberCount }})
            </a>
        </div>

        <!-- Search & Status Filter Controls -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <input type="hidden" name="role" value="{{ $roleFilter }}">

            <div class="flex flex-col sm:flex-row items-center gap-3 flex-1">
                <!-- Search Box -->
                <div class="relative w-full sm:w-80">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari nama, email, username, no HP..."
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 pl-9"
                    >
                    <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <!-- Status Filter Dropdown -->
                <div class="w-full sm:w-48">
                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                        <option value="all" @selected($statusFilter === 'all')>Semua Status</option>
                        <option value="active" @selected($statusFilter === 'active')>Status: Aktif Saja</option>
                        <option value="suspended" @selected($statusFilter === 'suspended')>Status: Nonaktif (Suspended)</option>
                    </select>
                </div>

                <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-charcoal-900 text-white text-sm font-semibold hover:bg-black transition-colors">
                    Filter
                </button>

                @if (request('q') || request('status') !== 'all')
                    <a href="{{ route('admin.users.index', ['role' => $roleFilter]) }}" class="px-3 py-2 rounded-xl border border-stone-300 text-xs text-stone-500 hover:bg-stone-50 text-center">
                        Reset Filter
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <x-admin.table :pagination="$users->links()">
        <x-slot:header>
            <th class="px-6 py-3.5">Akun Pengguna</th>
            <th class="px-6 py-3.5">Role</th>
            <th class="px-6 py-3.5">Status Akun</th>
            <th class="px-6 py-3.5">No. WhatsApp</th>
            <th class="px-6 py-3.5">Pesanan</th>
            <th class="px-6 py-3.5">Terdaftar</th>
            <th class="px-6 py-3.5 text-right">Aksi</th>
        </x-slot:header>

        @forelse ($users as $user)
            <tr class="hover:bg-stone-50/70 transition-colors {{ $user->isSuspended() ? 'bg-rose-50/20' : '' }}">
                <!-- User Profile & Name -->
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-stone-200 shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-full {{ $user->isAdmin() ? 'bg-champagne-100 text-champagne-800 border border-champagne-300' : 'bg-stone-100 text-stone-700 border border-stone-200' }} flex items-center justify-center font-bold text-sm shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="font-semibold text-charcoal-900 hover:text-champagne-700 transition-colors">
                                    {{ $user->name }}
                                </a>
                                @if($user->id === auth()->id())
                                    <span class="text-[9px] uppercase font-bold px-1.5 py-0.5 rounded-full bg-champagne-100 text-champagne-800 border border-champagne-200">
                                        Anda
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs text-stone-500 block">{{ $user->email }}</span>
                            @if($user->username)
                                <span class="text-[11px] font-mono text-stone-400 block">&#64;{{ $user->username }}</span>
                            @endif
                        </div>
                    </div>
                </td>

                <!-- Role Badge -->
                <td class="px-6 py-4">
                    @if ($user->isAdmin())
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-champagne-50 text-champagne-800 border border-champagne-300">
                            👑 Administrator
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            🛍️ Member
                        </span>
                    @endif
                </td>

                <!-- Account Status & Quick Toggle -->
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        @if ($user->isActive())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                Suspended
                            </span>
                        @endif

                        @if ($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    title="{{ $user->isActive() ? 'Nonaktifkan akun pengguna ini' : 'Aktifkan kembali akun ini' }}"
                                    class="text-[11px] font-medium underline text-stone-500 hover:text-charcoal-900"
                                >
                                    {{ $user->isActive() ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </td>

                <!-- Phone -->
                <td class="px-6 py-4 text-xs font-mono text-charcoal-800">
                    {{ $user->phone ?? '-' }}
                </td>

                <!-- Order Count -->
                <td class="px-6 py-4 text-xs">
                    @if ($user->orders_count > 0)
                        <span class="font-bold text-charcoal-900">{{ $user->orders_count }} pesanan</span>
                    @else
                        <span class="text-stone-400">0</span>
                    @endif
                </td>

                <!-- Registration Date -->
                <td class="px-6 py-4 text-xs text-stone-500 whitespace-nowrap">
                    {{ $user->created_at->translatedFormat('d M Y') }}
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right whitespace-nowrap text-xs font-semibold">
                    <div class="flex items-center justify-end gap-2">
                        <a
                            href="{{ route('admin.users.show', $user) }}"
                            class="px-2.5 py-1.5 rounded-lg border border-stone-200 text-stone-700 hover:bg-stone-50 transition-colors"
                            title="Lihat Detail & Riwayat"
                        >
                            Detail
                        </a>

                        <a
                            href="{{ route('admin.users.edit', $user) }}"
                            class="px-2.5 py-1.5 rounded-lg bg-stone-100 text-charcoal-800 hover:bg-stone-200 transition-colors"
                            title="Edit Data & Role"
                        >
                            Edit
                        </a>

                        @if ($user->id !== auth()->id() && $user->orders_count == 0)
                            <form
                                method="POST"
                                action="{{ route('admin.users.destroy', $user) }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }} secara permanen? Data yang dihapus tidak dapat dipulihkan.');"
                                class="inline"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="px-2.5 py-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition-colors"
                                    title="Hapus Akun"
                                >
                                    Hapus
                                </button>
                            </form>
                        @elseif($user->orders_count > 0)
                            <button
                                type="button"
                                disabled
                                title="Akun memiliki riwayat pesanan aktif; gunakan Nonaktifkan akun alih-alih menghapus data transaksi."
                                class="px-2.5 py-1.5 rounded-lg text-stone-300 cursor-not-allowed"
                            >
                                Terkunci
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-stone-500">
                    <div class="max-w-xs mx-auto">
                        <svg class="w-10 h-10 text-stone-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <p class="font-medium text-charcoal-900 text-sm">Tidak ada pengguna ditemukan</p>
                        <p class="text-xs text-stone-400 mt-1">Coba sesuaikan kata kunci pencarian atau tab filter di atas.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </x-admin.table>
</x-admin-layout>
