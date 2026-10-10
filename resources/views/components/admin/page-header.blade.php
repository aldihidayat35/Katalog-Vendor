@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8']) }}>
    <div>
        <h2 class="text-2xl font-serif font-bold text-charcoal-900 leading-tight">
            {{ $title }}
        </h2>
        @if ($subtitle)
            <p class="text-sm text-stone-500 mt-1">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    @if (isset($actions))
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @elseif ($slot->isNotEmpty())
        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
