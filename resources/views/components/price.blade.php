@props([
    'price',
    'discountPrice' => null,
    'size' => 'md',
])

@php
$hasDiscount = !empty($discountPrice) && $discountPrice > 0 && $discountPrice < $price;

$textSizes = match($size) {
    'sm' => ['main' => 'text-sm font-semibold', 'strike' => 'text-xs'],
    'lg' => ['main' => 'text-2xl font-bold', 'strike' => 'text-sm'],
    default => ['main' => 'text-base font-semibold', 'strike' => 'text-xs'],
};
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-baseline gap-2 flex-wrap']) }}>
    @if($hasDiscount)
        <span class="{{ $textSizes['main'] }} text-champagne-700">
            {{ rupiah($discountPrice) }}
        </span>
        <span class="{{ $textSizes['strike'] }} text-stone-400 line-through">
            {{ rupiah($price) }}
        </span>
    @else
        <span class="{{ $textSizes['main'] }} text-charcoal-900">
            {{ rupiah($price) }}
        </span>
    @endif
</div>
