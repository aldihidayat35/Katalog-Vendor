@props([
    'pagination' => null,
])

<div class="bg-white rounded-2xl border border-stone-200/80 shadow-subtle overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-stone-200 text-left text-sm text-charcoal-700">
            @if (isset($header))
                <thead class="bg-stone-50/80 text-stone-500 uppercase tracking-wider text-[11px] font-semibold border-b border-stone-200">
                    <tr>
                        {{ $header }}
                    </tr>
                </thead>
            @endif

            <tbody class="divide-y divide-stone-100 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if ($pagination || isset($footer))
        <div class="px-6 py-4 border-t border-stone-100 bg-stone-50/50">
            @if (isset($footer))
                {!! $footer !!}
            @elseif ($pagination)
                {!! $pagination !!}
            @endif
        </div>
    @endif
</div>
