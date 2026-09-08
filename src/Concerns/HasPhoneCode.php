<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Closure;

trait HasPhoneCode
{
    protected bool|Closure $phone = false;

    /** Append the dialling code to each option label. */
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
