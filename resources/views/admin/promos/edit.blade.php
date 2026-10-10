<x-admin-layout>
    <x-slot:heading>Edit Kupon Promo</x-slot:heading>

    <x-admin.page-header
        title="Edit Promo: {{ $promo->code }}"
        subtitle="Perbarui parameter diskon, masa aktif, atau batas pemakaian promo."
    >
        <x-slot:actions>
            <x-button href="{{ route('admin.promos.index') }}" variant="outline" size="md">
                &larr; Kembali ke Daftar
            </x-button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="max-w-3xl bg-white p-6 sm:p-8 rounded-3xl border border-stone-200/80 shadow-subtle">
        <form
            method="POST"
            action="{{ route('admin.promos.update', $promo) }}"
            x-data="{
                code: '{{ old('code', $promo->code) }}',
                discountType: '{{ old('discount_type', $promo->discount_type) }}',
            }"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Code -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Kode Promo <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        name="code"
                        x-model="code"
                        @input="code = code.toUpperCase().replace(/\s+/g, '')"
                        value="{{ old('code', $promo->code) }}"
                        required
                        class="w-full text-sm font-mono uppercase font-bold tracking-wider rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('code')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Nama Promo <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $promo->name) }}"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discount Type -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Tipe Diskon <span class="text-rose-500">*</span></label>
                    <select
                        name="discount_type"
                        x-model="discountType"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                        <option value="percent">Persentase (%)</option>
                        <option value="nominal">Nominal Tetap (Rp)</option>
                    </select>
                    @error('discount_type')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discount Amount -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">
                        <span x-text="discountType === 'percent' ? 'Besaran Diskon (%)' : 'Besaran Diskon (Rp)'"></span>
                        <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="discount_amount"
                        value="{{ old('discount_amount', $promo->discount_amount) }}"
                        min="1"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('discount_amount')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Discount -->
                <div x-show="discountType === 'percent'">
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Maksimal Diskon (Rp, Opsional)</label>
                    <input
                        type="number"
                        name="max_discount"
                        value="{{ old('max_discount', $promo->max_discount) }}"
                        min="0"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('max_discount')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Min Purchase -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Minimal Pembelian (Rp, Opsional)</label>
                    <input
                        type="number"
                        name="min_purchase"
                        value="{{ old('min_purchase', $promo->min_purchase) }}"
                        min="0"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('min_purchase')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Usage Limit & Used Count -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Batas Penggunaan (Kuota, Opsional)</label>
                    <input
                        type="number"
                        name="usage_limit"
                        value="{{ old('usage_limit', $promo->usage_limit) }}"
                        min="1"
                        placeholder="Kosongkan jika tanpa batas"
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    <span class="text-[11px] text-stone-400 mt-1 block">Telah digunakan: <strong>{{ $promo->used_count }} kali</strong></span>
                    @error('usage_limit')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Tanggal Mulai <span class="text-rose-500">*</span></label>
                    <input
                        type="date"
                        name="start_date"
                        value="{{ old('start_date', $promo->start_date->format('Y-m-d')) }}"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('start_date')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-2">Tanggal Berakhir <span class="text-rose-500">*</span></label>
                    <input
                        type="date"
                        name="end_date"
                        value="{{ old('end_date', $promo->end_date->format('Y-m-d')) }}"
                        required
                        class="w-full text-sm rounded-xl border-stone-300 focus:border-champagne-500 focus:ring-champagne-500"
                    >
                    @error('end_date')
                        <p class="text-xs text-rose-600 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- is_active toggle -->
            <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', $promo->is_active))
                        class="rounded border-stone-300 text-champagne-600 focus:ring-champagne-500 w-5 h-5"
                    >
                    <span class="text-sm font-semibold text-charcoal-900">Aktifkan Kode Promo Ini</span>
                </label>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-stone-100">
                <x-button href="{{ route('admin.promos.index') }}" variant="outline" size="md">
                    Batal
                </x-button>
                <x-button type="submit" variant="primary" size="md">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</x-admin-layout>
