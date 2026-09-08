<?php

namespace TantHammar\FilamentCountrySelect\Forms\Components;

use Filament\Forms\Components\Select;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryData;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryList;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryOptions;
use TantHammar\FilamentCountrySelect\Concerns\HasFlags;
use TantHammar\FilamentCountrySelect\Concerns\HasPhoneCode;

class CountrySelect extends Select
{
    use HasCountryData;
    use HasCountryList;
    use HasCountryOptions;
    use HasFlags;
    use HasPhoneCode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->native(false);
        $this->allowHtml(fn (): bool => $this->getShowFlags());
        $this->optionsLimit(config('filament-country-select.options-limit'));

        $this->searchable();

        $this->getSearchResultsUsing(fn (string $search): array => $this->buildOptions(
            $this->matching($search)
        ));

        $this->getOptionLabelUsing(function ($value): ?string {
            $country = $this->getCountry($value);

            return $country === null
                ? null
                : $this->buildOptions([$country])[$country['key']] ?? null;
        });
    }

    protected function rendersHtmlOptions(): bool
    {
        return $this->getShowFlags();
    }

    /**
     * @return array<int, array{key: string, iso_code: ?string, label: string, dial_code: ?string}>
     */
    protected function matching(string $search): array
    {
        $searchesDialCodes = $this->wantsDialCode();

        return array_filter(
            $this->getCountriesData(),
            fn (array $country): bool => stripos($country['label'], $search) !== false
                || ($searchesDialCodes && $country['dial_code'] !== null && str_contains($country['dial_code'], $search))
        );
    }
}
