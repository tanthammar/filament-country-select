<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Closure;

trait HasCountryList
{
    protected array|Closure $only = [];

    protected array|Closure $exclude = [];

    protected array|Closure $add = [];

    protected array|Closure $putFirst = [];

    public function only(array|Closure $countries): static
    {
        $this->only = $countries;

        return $this;
    }

    public function exclude(array|Closure $countries): static
    {
        $this->exclude = $countries;

        return $this;
    }

    /** Lift these countries to the top of the list, in the order given. */
    public function putFirst(array|Closure $countries): static
    {
        $this->putFirst = $countries;

        return $this;
    }

    /** Entries of your own, as code => label. Not countries, so no flag and no dialling code. */
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

    /** @return array<int, string> */
    public function getPutFirst(): array
    {
        return array_map(strtoupper(...), $this->evaluate($this->putFirst));
    }

    /** @return array<string, string> */
    public function getAdd(): array
    {
        return $this->evaluate($this->add);
    }
}
