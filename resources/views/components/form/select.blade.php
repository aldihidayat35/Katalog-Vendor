@props([
    'label' => null,
    'id' => null,
    'name',
    'required' => false,
    'hint' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
])

@php
$id = $id ?? $name;
$selectedValue = old($name, $selected);
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
        <select
            name="{{ $name }}"
            id="{{ $id }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full rounded-xl border-stone-300 bg-white px-4 py-2.5 text-sm text-charcoal-900 shadow-sm transition-colors focus:border-champagne-500 focus:ring-1 focus:ring-champagne-500 disabled:bg-stone-50' . ($errors->has($name) ? ' border-rose-400 focus:border-rose-500 focus:ring-rose-500' : '')
            ]) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif

            @if(!empty($options))
                @foreach($options as $val => $optLabel)
                    <option value="{{ $val }}" @selected((string)$selectedValue === (string)$val)>
                        {{ $optLabel }}
                    </option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>
    </div>

    @if($hint && !$errors->has($name))
        <p class="text-xs text-stone-500">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p>
    @enderror
</div>
