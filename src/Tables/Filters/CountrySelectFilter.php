<?php

namespace TantHammar\FilamentCountrySelect\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryData;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryList;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryOptions;
use TantHammar\FilamentCountrySelect\Concerns\HasPhoneCode;

/**
 * Flags are deliberately not offered here. SelectFilter builds its own inner Select and never
 * allows html on it, so an <img> would show as markup, and a method that quietly does nothing
 * is worse than one that is not there.
 */
class CountrySelectFilter extends SelectFilter
{
    use HasCountryData;
    use HasCountryList;
    use HasCountryOptions;
    use HasPhoneCode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->native(false);
        $this->optionsLimit(config('filament-country-select.options-limit'));

        $this->searchable();

        $this->getSearchResultsUsing(fn (string $search): array => $this->buildOptions(array_filter(
            $this->getCountriesData(),
            fn (array $country): bool => stripos($country['label'], $search) !== false
        )));
    }
}
