<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Closure;

trait HasPhoneCode
{
    protected bool|Closure $phone = false;

    /** Append the international dialling code to each country. */
    public function phone(bool|Closure $phone = true): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone(): bool
    {
        return $this->evaluate($this->phone);
    }
}
