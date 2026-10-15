<x-admin-layout>
    <x-slot:heading>Detail Pesanan {{ $order->order_number }}</x-slot:heading>

    <x-admin.page-header
        title="Pesanan {{ $order->order_number }}"
        subtitle="Diterima pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB"
    >
        <x-slot:actions>
            <a
                href="{{ $waCustomerUrl }}"
                target="_blank"
                class="inline-flex items-center px-4 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 shadow-sm transition-colors"
            >
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                Hubungi via WhatsApp
            </a>
            <x-button href="{{ route('admin.orders.index') }}" variant="outline" size="md">
                &larr; Kembali ke Daftar
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Order Items & Customer Details (2 Cols) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Customer Information Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle">
                <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3 mb-4">
                    Data Pemesan
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Nama Customer</span>
                        <span class="font-medium text-charcoal-900">{{ $order->customer_name }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Email</span>
                        <span class="font-medium text-charcoal-900">{{ $order->customer_email }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">No. WhatsApp / HP</span>
                        <span class="font-medium text-charcoal-900">{{ $order->customer_phone }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Instagram</span>
                        <span class="font-medium text-charcoal-900">{{ $order->instagram_account ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Tanggal Acara Pernikahan</span>
                        <span class="font-medium text-charcoal-900">
                            {{ $order->event_date ? $order->event_date->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Metode Pengiriman</span>
                        <span class="font-medium text-charcoal-900">{{ $order->shipping_method }}</span>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Ongkos Kirim</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Rp 0 &bull; Free Ongkir Seluruh Riau
                        </span>
                    </div>

                    <div class="sm:col-span-2">
                        <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Alamat Pengiriman</span>
                        <p class="font-medium text-charcoal-900 mt-0.5 whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>

                    @if ($order->notes)
                        <div class="sm:col-span-2 bg-stone-50 p-3 rounded-xl border border-stone-200">
                            <span class="text-xs font-semibold text-stone-500 uppercase tracking-wider block">Catatan dari Customer:</span>
                            <p class="text-sm text-charcoal-800 mt-1">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Ordered Items (Snapshot) -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle">
                <h3 class="text-lg font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-3 mb-4">
                    Rincian Item Pesanan (Snapshot)
                </h3>

                <div class="divide-y divide-stone-100">
                    @foreach ($order->items as $item)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                @if ($item->product)
                                    <img src="{{ $item->product->primary_image_url }}" class="w-14 h-14 rounded-xl object-cover border border-stone-200 shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-stone-100 flex items-center justify-center text-stone-400 shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="font-semibold text-charcoal-900">{{ $item->product_name }}</h4>
                                        @if ($item->isWholesale())
                                            <span class="px-2 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">
                                                Harga Grosir
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-stone-500 mt-0.5">
                                        Harga Satuan: {{ rupiah($item->unit_price) }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-right sm:shrink-0">
                                <span class="text-xs text-stone-500 block">{{ $item->quantity }} pcs</span>
                                <span class="font-semibold text-charcoal-900 block text-sm">
                                    {{ rupiah($item->subtotal) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Price Breakdown -->
                <div class="pt-4 border-t border-stone-100 space-y-2 text-sm">
                    <div class="flex justify-between text-stone-600">
                        <span>Subtotal Pesanan</span>
                        <span class="font-medium text-charcoal-900">{{ rupiah($order->subtotal) }}</span>
                    </div>

                    <div class="flex justify-between items-center text-stone-600">
                        <span>Ongkos Kirim</span>
                        <span class="font-semibold text-emerald-700 inline-flex items-center gap-1.5">
                            <span class="text-[10px] px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200 font-bold">Free Ongkir Se-Riau</span>
                            <span>Rp 0</span>
                        </span>
                    </div>

                    @if ($order->discount_total > 0)
                        <div class="flex justify-between text-emerald-700">
                            <span>Diskon Voucher ({{ $order->promo_code }})</span>
                            <span class="font-medium">-{{ rupiah($order->discount_total) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-base font-bold text-charcoal-900 pt-2 border-t border-stone-100">
                        <span>Total Nilai Pesanan</span>
                        <span class="text-champagne-700">{{ rupiah($order->total_price) }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. WhatsApp Message Box & Copy Button -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle"
                 x-data="{
                    copied: false,
                    copyMessage() {
                        const text = $refs.waMsg.value;
                        navigator.clipboard.writeText(text).then(() => {
                            this.copied = true;
                            setTimeout(() => this.copied = false, 2500);
                        });
                    }
                 }">
                <div class="flex items-center justify-between border-b border-stone-100 pb-3 mb-4">
                    <div>
                        <h3 class="text-lg font-serif font-bold text-charcoal-900">Format Pesan WhatsApp Pesanan</h3>
                        <p class="text-xs text-stone-500">Pesan yang otomatis dikompilasi saat checkout</p>
                    </div>
                    <button
                        type="button"
                        @click="copyMessage()"
                        class="inline-flex items-center px-3 py-1.5 rounded-xl border border-stone-300 text-xs font-semibold text-stone-700 hover:bg-stone-50 transition-colors"
                    >
                        <span x-text="copied ? 'Tersalin! ✓' : 'Salin Pesan'"></span>
                    </button>
                </div>

                <textarea
                    x-ref="waMsg"
                    rows="8"
                    readonly
                    class="w-full text-xs font-mono bg-stone-50 rounded-2xl border-stone-200 text-charcoal-800 p-4 focus:ring-0 leading-relaxed"
                >{{ $order->whatsapp_message }}</textarea>
            </div>
        </div>

        <!-- Right: Status Update & Admin Notes (1 Col) -->
        <div class="space-y-6">
            
            <!-- Update Status & Notes Form -->
            <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-subtle space-y-4">
                <h3 class="text-base font-serif font-bold text-charcoal-900 border-b border-stone-100 pb-2">
                    Kelola Status Pesanan
                </h3>

                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1.5">Status Saat Ini</label>
                        <div class="mb-3">
                            <x-admin.status-badge :status="$order->status" type="order" />
                        </div>

                        <select name="status" required class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500">
                            <option value="lead" @selected($order->status === 'lead')>Lead Baru (Menunggu Follow-up)</option>
                            <option value="contacted" @selected($order->status === 'contacted')>Sudah Dihubungi (Follow-up Desain/Data)</option>
                            <option value="confirmed" @selected($order->status === 'confirmed')>Dikonfirmasi / Dalam Produksi</option>
                            <option value="completed" @selected($order->status === 'completed')>Pesanan Selesai</option>
                            <option value="cancelled" @selected($order->status === 'cancelled')>Dibatalkan</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-stone-500 uppercase tracking-wider mb-1.5">Catatan Internal Admin</label>
                        <textarea
                            name="admin_notes"
                            rows="4"
                            placeholder="Tulis catatan follow-up, status desain, resi pengiriman, atau catatan internal tim..."
                            class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                        >{{ old('admin_notes', $order->admin_notes) }}</textarea>
                        @error('admin_notes')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full py-2.5 rounded-xl bg-charcoal-900 text-white text-sm font-semibold hover:bg-black transition-colors"
                    >
                        Simpan Perubahan Status
                    </button>
                </form>
            </div>

            <!-- Customer Account Info -->
            <div class="bg-white p-6 rounded-3xl border border-stone-200/80 shadow-subtle space-y-3 text-sm">
                <h4 class="text-xs font-semibold text-stone-500 uppercase tracking-wider">Info Akun Member</h4>
                @if ($order->user)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-champagne-100 text-champagne-800 flex items-center justify-center font-bold">
                            {{ substr($order->user->name, 0, 1) }}
                        </div>
                        <div>
                            <span class="font-semibold text-charcoal-900 block">{{ $order->user->name }}</span>
                            <span class="text-xs text-stone-500 block">{{ $order->user->email }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.members.show', $order->user) }}" class="text-xs font-semibold text-champagne-700 hover:underline block pt-2">
                        Lihat Profil Member &rarr;
                    </a>
                @else
                    <span class="text-stone-400 text-xs">Pesanan dari pengguna non-terdaftar (Guest).</span>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
