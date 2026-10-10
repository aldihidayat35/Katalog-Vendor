@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none rounded-full';

$sizeClasses = match($size) {
    'sm' => 'px-3.5 py-1.5 text-xs',
    'lg' => 'px-7 py-3 text-base shadow-md',
    default => 'px-5 py-2.5 text-sm',
};

$variantClasses = match($variant) {
    'primary' => 'bg-champagne hover:bg-champagne-600 text-white shadow-sm focus:ring-champagne-400 active:bg-champagne-700',
    'secondary' => 'bg-charcoal hover:bg-charcoal-700 text-white focus:ring-charcoal-500',
    'outline' => 'border border-champagne text-champagne-700 hover:bg-champagne-50 focus:ring-champagne-300',
    'ghost' => 'text-charcoal-700 hover:bg-stone-100 focus:ring-stone-300',
    'danger' => 'bg-rose-600 hover:bg-rose-700 text-white focus:ring-rose-500',
    'whatsapp' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm focus:ring-emerald-400 active:bg-emerald-800',
    default => 'bg-champagne hover:bg-champagne-600 text-white',
};

$classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
