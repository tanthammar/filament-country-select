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
        // Only a flag needs markup. Without one an option is just its name, which Filament can
        // escape and render as an ordinary option.
        $this->allowHtml(fn (): bool => $this->getShowFlags());
        $this->optionsLimit(config('filament-country-select.options-limit'));

        $this->searchable();

        $this->getSearchResultsUsing(fn (string $search): array => $this->buildOptions(
            $this->matching($search)
        ));

        // The label of the one selected country, looked up and built on its own. Reading it out
        // of the whole option list would build all 246 of them a second time per render.
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
     * The countries a search matches, by name and, when dialling codes are shown, by code.
     *
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
