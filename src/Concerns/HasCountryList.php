<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Closure;

/**
 * Shapes which countries a component offers. Renaming a country's stored code is deliberately
 * not offered: the stored value is always the ISO code, and a form that needs to read or write
 * something else can say so with Filament's own formatStateUsing() and dehydrateStateUsing().
 */
trait HasCountryList
{
    protected array|Closure $only = [];

    protected array|Closure $exclude = [];

    protected array|Closure $add = [];

    /** Show only these countries, in the order the package lists them. */
    public function only(array|Closure $countries): static
    {
        $this->only = $countries;

        return $this;
    }

    /** Show every country but these. */
    public function exclude(array|Closure $countries): static
    {
        $this->exclude = $countries;

        return $this;
    }

    /**
     * Add entries of your own, as code => label, such as ['XX' => 'Other'].
     * They are not countries, so they carry no flag and no dialling code.
     */
    public function add(array|Closure $countries): static
    {
        $this->add = $countries;

        return $this;
    }

    /** @return array<int, string> */
    public function getOnly(): array
    {
        return array_map(strtoupper(...), $this->evaluate($this->only));
    }

    /** @return array<int, string> */
    public function getExclude(): array
    {
        return array_map(strtoupper(...), $this->evaluate($this->exclude));
    }

    /** @return array<string, string> */
    public function getAdd(): array
    {
        return $this->evaluate($this->add);
    }
}
