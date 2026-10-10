@props([
    'label' => null,
    'id' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
])

@php
$id = $id ?? $name;
@endphp

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-semibold uppercase tracking-wider text-charcoal-700">
            {{ $label }}
            @if($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ old($name, $value) }}"
            {{ $required ? 'required' : '' }}
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'w-full rounded-xl border-stone-300 bg-white px-4 py-2.5 text-sm text-charcoal-900 shadow-sm transition-colors focus:border-champagne-500 focus:ring-1 focus:ring-champagne-500 disabled:bg-stone-50 disabled:text-stone-400 placeholder:text-stone-400' . ($errors->has($name) ? ' border-rose-400 focus:border-rose-500 focus:ring-rose-500' : '')
            ]) }}
        />
    </div>

    @if($hint && !$errors->has($name))
        <p class="text-xs text-stone-500">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
    @enderror
</div>
