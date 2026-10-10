@props([
    'title',
    'value',
    'description' => null,
    'color' => 'champagne', // champagne, sage, blue, purple, amber, emerald, rose
])

@php
$colorClasses = match ($color) {
    'sage' => 'bg-emerald-50 text-emerald-700',
    'blue' => 'bg-blue-50 text-blue-600',
    'purple' => 'bg-purple-50 text-purple-600',
    'amber' => 'bg-amber-50 text-amber-600',
    'emerald' => 'bg-emerald-50 text-emerald-600',
    'rose' => 'bg-rose-50 text-rose-600',
    default => 'bg-champagne-50 text-champagne-700',
};
@endphp

<div {{ $attributes->merge(['class' => 'bg-white p-6 rounded-2xl border border-stone-200/80 shadow-subtle flex items-center justify-between hover:border-champagne-300 transition-colors']) }}>
    <div class="min-w-0 pr-4">
        <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider truncate">
            {{ $title }}
        </p>
        <h3 class="text-2xl font-bold font-serif text-charcoal-900 mt-1 truncate">
            {{ $value }}
        </h3>
        @if ($description)
            <p class="text-xs text-stone-400 mt-0.5 truncate">
                {{ $description }}
            </p>
        @endif
    </div>

    @if (isset($icon))
        <div class="w-12 h-12 rounded-xl {{ $colorClasses }} flex items-center justify-center shrink-0">
            {{ $icon }}
        </div>
    @endif
</div>
