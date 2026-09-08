<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Closure;

trait HasFlags
{
    protected bool|Closure $showFlags = false;

    /** The flag files have to be published first. */
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
