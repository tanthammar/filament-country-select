<?php

use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;
use TantHammar\FilamentCountrySelect\Tables\Columns\CountryColumn;

// Filament resolves a column's state from its record, which a table has to supply.
class StatefulCountryColumn extends CountryColumn
{
    protected mixed $fakeState = null;

    public function fakeState(mixed $state): static
    {
        $this->fakeState = $state;

        return $this;
    }

    public function getState(): mixed
    {
        return $this->fakeState;
    }
}

it('renders the country name', function () {
    $html = StatefulCountryColumn::make('country_code')->fakeState('SE')->toEmbeddedHtml();

    expect($html)->toContain('Sweden')
        ->and($html)->toContain('fi-ta-country')
        ->and($html)->not->toContain('<img');
});

it('renders a flag only when asked to', function () {
    $html = StatefulCountryColumn::make('country_code')->fakeState('SE')->showFlags()->toEmbeddedHtml();

    expect($html)->toContain('flags/SE.png')
        ->and($html)->toContain('<img')
        ->and($html)->toContain('Sweden');
});

it('renders a country stored as a backed enum', function () {
    $html = StatefulCountryColumn::make('country_code')->fakeState(CountriesEnum::SE)->showFlags()->toEmbeddedHtml();

    expect($html)->toContain('Sweden')
        ->and($html)->toContain('flags/SE.png');
});

it('appends the dialling code when asked to', function () {
    $html = StatefulCountryColumn::make('country_code')->fakeState('SE')->phone()->toEmbeddedHtml();

    expect($html)->toContain('Sweden +46');
});

it('escapes the label', function () {
    $html = StatefulCountryColumn::make('country_code')->fakeState('XX')->add(['XX' => '<b>Other</b>'])->toEmbeddedHtml();

    expect($html)->toContain('&lt;b&gt;Other&lt;/b&gt;')
        ->and($html)->not->toContain('<b>Other</b>');
});

it('falls back to the stored value when it names no country', function () {
    $html = StatefulCountryColumn::make('country_code')->fakeState('nope')->showFlags()->toEmbeddedHtml();

    expect($html)->toContain('nope')
        ->and($html)->not->toContain('<img');
});

it('renders the placeholder when there is nothing stored', function () {
    $html = StatefulCountryColumn::make('country_code')->fakeState(null)->placeholder('None')->toEmbeddedHtml();

    expect($html)->toContain('fi-ta-placeholder')
        ->and($html)->toContain('None');
});
