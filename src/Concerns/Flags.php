<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

trait Flags
{
    /** Filament icon ratio is 4:3. Avoid squashing icon. */
    public function getIcon(): Htmlable
    {
        return $this->getFlag('size-full object-contain');
    }

    /**
     * Flags must be published.
     */
    public function getFlag(string $class = 'h-5 w-6 shrink-0 object-contain'): HtmlString
    {
        return new HtmlString(
            '<img src="'.e($this->getFlagUrl()).'" alt="" width="32" height="24" class="'.e($class).'" loading="lazy">'
        );
    }

    public function getFlagUrl(): string
    {
        $path = trim((string) config('filament-country-select.flags-path'), '/');

        return asset($path.'/'.$this->value.'.png');
    }

    /** Depending on the reader's system, this might render as the two letters instead of a flag. */
    public function getEmojiFlag(): string
    {
        return collect(mb_str_split($this->value))
            ->map(fn (string $letter): string => mb_chr(0x1F1E6 + ord($letter) - ord('A')))
            ->implode('');
    }
}
