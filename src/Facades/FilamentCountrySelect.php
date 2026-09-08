<?php

namespace TantHammar\FilamentCountrySelect\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TantHammar\FilamentCountrySelect\FilamentCountrySelect
 */
class FilamentCountrySelect extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TantHammar\FilamentCountrySelect\FilamentCountrySelect::class;
    }
}
