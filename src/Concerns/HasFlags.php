<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Closure;

trait HasFlags
{
    protected bool|Closure $showFlags = false;

    /**
     * Show a flag beside every country. Off by default, because the flags are image files that
     * have to be published first.
     *
     * @see https://github.com/tanthammar/filament-country-select#flags
     */
    public function showFlags(bool|Closure $showFlags = true): static
    {
        $this->showFlags = $showFlags;

        return $this;
    }

    public function getShowFlags(): bool
    {
        return $this->evaluate($this->showFlags);
    }
}
