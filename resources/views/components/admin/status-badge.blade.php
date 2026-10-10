@props([
    'status' => null,
    'type' => 'order', // order, stock, promo, boolean
    'label' => null,
])

@php
$config = match ($type) {
    'order' => match ($status) {
        'lead' => ['bg' => 'bg-amber-100 text-amber-800 border-amber-200', 'label' => 'Lead Baru'],
        'contacted' => ['bg' => 'bg-blue-100 text-blue-800 border-blue-200', 'label' => 'Dihubungi'],
        'confirmed' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Dikonfirmasi'],
        'completed' => ['bg' => 'bg-purple-100 text-purple-800 border-purple-200', 'label' => 'Selesai'],
        'cancelled' => ['bg' => 'bg-rose-100 text-rose-800 border-rose-200', 'label' => 'Dibatalkan'],
        default => ['bg' => 'bg-stone-100 text-stone-700 border-stone-200', 'label' => ucfirst($status ?? '-')],
    },
    'stock' => match ($status) {
        'ready' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Ready Stock'],
        'preorder' => ['bg' => 'bg-amber-100 text-amber-800 border-amber-200', 'label' => 'Pre-order'],
        'out_of_stock' => ['bg' => 'bg-rose-100 text-rose-800 border-rose-200', 'label' => 'Stok Habis'],
        default => ['bg' => 'bg-stone-100 text-stone-700 border-stone-200', 'label' => ucfirst($status ?? '-')],
    },
    'promo' => match ($status) {
        'active' => ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Aktif'],
        'pending' => ['bg' => 'bg-sky-100 text-sky-800 border-sky-200', 'label' => 'Belum Mulai'],
        'expired' => ['bg' => 'bg-rose-100 text-rose-800 border-rose-200', 'label' => 'Kedaluwarsa'],
        'exhausted' => ['bg' => 'bg-amber-100 text-amber-800 border-amber-200', 'label' => 'Kuota Habis'],
        'inactive' => ['bg' => 'bg-stone-100 text-stone-600 border-stone-200', 'label' => 'Nonaktif'],
        default => ['bg' => 'bg-stone-100 text-stone-700 border-stone-200', 'label' => ucfirst($status ?? '-')],
    },
    'boolean' => $status ? 
        ['bg' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'label' => 'Aktif'] : 
        ['bg' => 'bg-stone-100 text-stone-600 border-stone-200', 'label' => 'Nonaktif'],
    default => ['bg' => 'bg-stone-100 text-stone-700 border-stone-200', 'label' => $status ?? '-'],
};

$displayLabel = $label ?? $config['label'];
$badgeClasses = $config['bg'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {$badgeClasses}"]) }}>
    {{ $displayLabel }}
</span>
