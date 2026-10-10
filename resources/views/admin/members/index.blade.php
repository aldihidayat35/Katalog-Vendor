<x-admin-layout>
    <x-slot:heading>Manajemen Member</x-slot:heading>

    <x-admin.page-header
        title="Daftar Pelanggan Terdaftar"
        subtitle="Lihat informasi kontak member, riwayat pesanan, dan unduh data untuk keperluan marketing."
    >
        <x-slot:actions>
            <x-button
                href="{{ route('admin.members.export', request()->query()) }}"
                variant="outline"
                size="md"
            >
                <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV Member
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <!-- Search Bar -->
    <div class="bg-white p-5 rounded-2xl border border-stone-200/80 shadow-subtle mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.members.index') }}" class="w-full sm:w-96 flex items-center gap-2">
            <div class="relative flex-1">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari nama, email, atau no. HP..."
                    class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500 pl-9"
                >
                <svg class="w-4 h-4 text-stone-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-charcoal-900 text-white text-sm font-semibold hover:bg-black transition-colors">
                Cari
            </button>
            @if (request('q'))
                <a href="{{ route('admin.members.index') }}" class="p-2 rounded-xl border border-stone-300 text-stone-500 hover:bg-stone-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            @endif
        </form>
    </div>

    <!-- Members Table -->
    <x-admin.table :pagination="$members->links()">
        <x-slot:header>
            <th class="px-6 py-3.5">Nama &amp; Email</th>
            <th class="px-6 py-3.5">No. HP / WhatsApp</th>
            <th class="px-6 py-3.5">Instagram</th>
            <th class="px-6 py-3.5">Total Pesanan</th>
            <th class="px-6 py-3.5">Tanggal Terdaftar</th>
            <th class="px-6 py-3.5 text-right">Aksi</th>
        </x-slot:header>

        @forelse ($members as $member)
            <tr class="hover:bg-stone-50/70 transition-colors">
                <!-- Name & Email -->
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-champagne-100 text-champagne-800 flex items-center justify-center font-bold text-xs shrink-0">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        <div>
                            <span class="font-semibold text-charcoal-900 block">{{ $member->name }}</span>
                            <span class="text-xs text-stone-500 block">{{ $member->email }}</span>
                        </div>
                    </div>
                </td>

                <!-- Phone -->
                <td class="px-6 py-4 text-sm text-charcoal-800 font-mono">
                    {{ $member->phone ?? '-' }}
                </td>

                <!-- Instagram -->
                <td class="px-6 py-4 text-sm text-stone-600">
                    @if ($member->instagram)
                        <span class="font-mono text-champagne-700">{{ $member->instagram }}</span>
                    @else
                        <span class="text-stone-400">-</span>
                    @endif
                </td>

                <!-- Order Count -->
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-800">
                        {{ $member->orders_count }} pesanan
                    </span>
                </td>

                <!-- Joined At -->
                <td class="px-6 py-4 text-xs text-stone-500">
                    {{ $member->created_at->translatedFormat('d M Y') }}
                </td>

                <!-- Actions -->
                <td class="px-6 py-4 text-right">
                    <a
                        href="{{ route('admin.members.show', $member) }}"
                        class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-semibold bg-stone-100 text-stone-700 hover:bg-champagne-100 hover:text-champagne-900 transition-colors"
                    >
                        Profil &amp; Pesanan &rarr;
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-stone-400 text-sm">
                    Tidak ada member terdaftar ditemukan.
                </td>
            </tr>
        @endforelse
    </x-admin.table>
</x-admin-layout>
