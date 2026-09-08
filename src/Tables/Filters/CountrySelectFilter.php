<?php

namespace TantHammar\FilamentCountrySelect\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryData;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryOptions;
use TantHammar\FilamentCountrySelect\Concerns\HasFlags;
use TantHammar\FilamentCountrySelect\Concerns\HasPhoneCode;

class CountrySelectFilter extends SelectFilter
{
    use HasCountryData;
    use HasCountryOptions;
    use HasFlags;
    use HasPhoneCode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->native(false);
        $this->optionsLimit(config('filament-country-select.options-limit'));

        $this->searchable();

        // A filter has no allowHtml, so its options stay plain text.
        $this->getSearchResultsUsing(fn (string $search): array => collect($this->getCountriesData())
            ->filter(fn (array $country): bool => stripos($country['label'], $search) !== false)
            ->mapWithKeys(fn (array $country): array => [$country['iso_code'] => $this->getPlainOption($country)])
            ->all());
    }

    /** @param  array{label: string, dial_code: string, iso_code: string}  $country */
    public function getOption(array $country): string
    {
        return $this->getPlainOption($country);
    }
}
