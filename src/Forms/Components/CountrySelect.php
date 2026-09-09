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
        $this->optionsLimit(config('filament-country-select.options-limit') ?? 50);

        $this->in(fn (CountrySelect $component): array => $component->getValidationKeys());

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

        $this->getOptionLabelsUsing(fn (array $values): array => $this->buildOptions(
            array_filter(array_map($this->getCountry(...), $values))
        ));
    }

    protected function rendersHtmlOptions(): bool
    {
        return $this->getShowFlags();
    }

    /**
     * @return array<int, string>
     */
    protected function getValidationKeys(): array
    {
        $countries = $this->getCountriesData();

        if (! $this->hasDisabledOptions()) {
            return array_column($countries, 'key');
        }

        $codes = [];

        foreach ($countries as $country) {
            if (! $this->isOptionDisabled($country['key'], $country['label'])) {
                $codes[] = $country['key'];
            }
        }

        return $codes;
    }
}
