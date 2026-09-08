<?php

namespace TantHammar\FilamentCountrySelect\Forms\Components;

use Filament\Forms\Components\Select;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryData;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryOptions;
use TantHammar\FilamentCountrySelect\Concerns\HasFlags;
use TantHammar\FilamentCountrySelect\Concerns\HasPhoneCode;

class CountrySelect extends Select
{
    use HasCountryData;
    use HasCountryOptions;
    use HasFlags;
    use HasPhoneCode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->native(false);
        $this->allowHtml();
        $this->optionsLimit(config('filament-country-select.options-limit'));

        $this->searchable();

        $this->getSearchResultsUsing(fn (string $search): array => collect($this->getCountriesData())
            ->filter(fn (array $country): bool => $this->matches($country, $search))
            ->mapWithKeys(fn (array $country): array => [$country['iso_code'] => $this->getOption($country)])
            ->all());

        // selected label
        $this->getOptionLabelUsing(fn ($value): ?string => $this->getCountries()[$value] ?? null);
    }

    /** @param  array{label: string, dial_code: string, iso_code: string}  $country */
    protected function matches(array $country, string $search): bool
    {
        if (stripos($country['label'], $search) !== false) {
            return true;
        }

        return $this->getPhone() && str_contains($country['dial_code'], $search);
    }
}
