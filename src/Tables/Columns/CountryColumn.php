<?php

namespace TantHammar\FilamentCountrySelect\Tables\Columns;

use Filament\Tables\Columns\Column;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryData;
use TantHammar\FilamentCountrySelect\Concerns\HasFlags;
use TantHammar\FilamentCountrySelect\Concerns\HasPhoneCode;

class CountryColumn extends Column
{
    use HasCountryData;
    use HasFlags;
    use HasPhoneCode;

    protected string $view = 'filament-country-select::tables.columns.country-column';
}
