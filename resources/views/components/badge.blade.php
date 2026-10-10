@props([
    'color' => 'gold',
    'size' => 'sm',
])

@php
$baseClasses = 'inline-flex items-center font-medium tracking-wide uppercase rounded-full';

$sizeClasses = match($size) {
    'md' => 'px-3 py-1 text-xs',
    default => 'px-2.5 py-0.5 text-[10px]',
};

$colorClasses = match($color) {
    'gold', 'champagne' => 'bg-champagne-100 text-champagne-800 border border-champagne-300/60',
    'green', 'sage' => 'bg-sage-100 text-sage-dark border border-sage-200',
    'rose', 'dustyrose' => 'bg-dustyrose-100 text-dustyrose-dark border border-dustyrose-200',
    'blue' => 'bg-blue-50 text-blue-700 border border-blue-200',
    'purple' => 'bg-purple-50 text-purple-700 border border-purple-200',
    'stone', 'charcoal' => 'bg-stone-100 text-stone-700 border border-stone-200',
    default => 'bg-champagne-100 text-champagne-800 border border-champagne-300/60',
};

$classes = "{$baseClasses} {$sizeClasses} {$colorClasses}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
