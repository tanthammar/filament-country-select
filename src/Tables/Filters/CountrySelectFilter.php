<?php

namespace TantHammar\FilamentCountrySelect\Tables\Filters;

use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Arr;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryData;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryList;
use TantHammar\FilamentCountrySelect\Concerns\HasCountryOptions;
use TantHammar\FilamentCountrySelect\Concerns\HasFlags;
use TantHammar\FilamentCountrySelect\Concerns\HasPhoneCode;

class CountrySelectFilter extends SelectFilter
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
        $this->optionsLimit(config('filament-country-select.options-limit') ?? 50);

        $this->searchable();

        $this->getSearchResultsUsing(fn (string $search): array => $this->buildOptions(
            $this->matching($search)
        ));

        $this->modifyFormFieldUsing(fn (Select $field) => $field
            ->allowHtml(fn () => $this->getShowFlags())
        );

        $this->indicateUsing(function (array $state): array {
            $labels = collect($this->isMultiple() ? ($state['values'] ?? []) : Arr::wrap($state['value'] ?? null))
                ->map(fn (mixed $value): ?string => $this->getCountryLabel(is_string($value) ? $value : null))
                ->filter()
                ->join(', ', ' & ');

            if (blank($labels)) {
                return [];
            }

            $indicator = $this->getIndicator();

            if (! $indicator instanceof Indicator) {
                $indicator = Indicator::make("{$indicator}: {$labels}");
            }

            return [$indicator];
        });
    }

    protected function rendersHtmlOptions(): bool
    {
        return $this->getShowFlags();
    }
}
