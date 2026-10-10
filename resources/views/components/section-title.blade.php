@props([
    'tag' => null,
    'title',
    'description' => null,
    'align' => 'center',
])

@php
$alignClasses = match($align) {
    'left' => 'text-left items-start',
    'right' => 'text-right items-end',
    default => 'text-center items-center mx-auto',
};
@endphp

<div {{ $attributes->merge(['class' => "flex flex-col max-w-2xl {$alignClasses} mb-12"]) }}>
    @if($tag)
        <span class="text-xs uppercase font-semibold tracking-widest text-champagne-600 mb-2">
            {{ $tag }}
        </span>
    @endif

    <h2 class="text-3xl sm:text-4xl font-serif text-charcoal-900 leading-tight mb-3">
        {{ $title }}
    </h2>

    @if($description)
        <p class="text-stone-600 text-sm sm:text-base leading-relaxed">
            {{ $description }}
        </p>
    @endif

    <div class="w-16 h-0.5 bg-champagne-300 mt-4 rounded-full"></div>
</div>
