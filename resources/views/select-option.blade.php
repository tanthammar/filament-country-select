<div class="flex gap-x-2">
    @if ($hasFlags)
        <div>
            <x-filament::icon
                alias="flags::{{ $iso_code }}"
                class="h-5 w-6"
            />
        </div>
    @endif
    <div>
        {{ $label }}

        @if (filled($dial_code))
            <span class="text-xs">
                {{ $dial_code }}
            </span>
        @endif
    </div>
</div>
