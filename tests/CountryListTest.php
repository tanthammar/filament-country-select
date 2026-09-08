<?php

use Filament\Support\Components\Contracts\HasEmbeddedView;
use TantHammar\FilamentCountrySelect\Forms\Components\CountrySelect;
use TantHammar\FilamentCountrySelect\Tables\Columns\CountryColumn;
use TantHammar\FilamentCountrySelect\Tables\Filters\CountrySelectFilter;

it('offers every country by default', function () {
    expect(CountrySelectFilter::make('country_code')->getCountries())->toHaveCount(246);
});

it('offers only the countries asked for', function () {
    $options = CountrySelectFilter::make('country_code')->only(['SE', 'no', 'DK'])->getCountries();

    expect(array_keys($options))->toBe(['DK', 'NO', 'SE']);
});

it('leaves out the countries excluded', function () {
    $options = CountrySelectFilter::make('country_code')->exclude(['SE', 'no'])->getCountries();

    expect($options)->toHaveCount(244)
        ->and(array_keys($options))->not->toContain('SE', 'NO');
});

it('excludes from what only offered', function () {
    $options = CountrySelectFilter::make('country_code')->only(['SE', 'NO', 'DK'])->exclude(['NO'])->getCountries();

    expect(array_keys($options))->toBe(['DK', 'SE']);
});

it('adds entries of its own after the countries', function () {
    $filter = CountrySelectFilter::make('country_code')->only(['SE'])->add(['XX' => 'Other']);

    expect($filter->getCountries())->toBe(['SE' => 'Sweden', 'XX' => 'Other'])
        ->and($filter->getCountryLabel('XX'))->toBe('Other')
        ->and($filter->getCountryFlag('XX'))->toBeNull()
        ->and($filter->getCountryFlag('SE'))->toBe('se');
});

it('gives an added entry no dialling code', function () {
    $filter = CountrySelectFilter::make('country_code')->only(['SE'])->add(['XX' => 'Other'])->phone();

    expect($filter->getCountries())->toBe(['SE' => 'Sweden +46', 'XX' => 'Other']);
});

it('takes closures', function () {
    $options = CountrySelectFilter::make('country_code')
        ->only(fn (): array => ['SE', 'DK'])
        ->exclude(fn (): array => ['DK'])
        ->add(fn (): array => ['XX' => 'Other'])
        ->getCountries();

    expect($options)->toBe(['SE' => 'Sweden', 'XX' => 'Other']);
});

it('shapes the list the select searches too', function () {
    $select = CountrySelect::make('country_code')->only(['SE', 'DK']);

    expect($select->getCountries())->toHaveCount(2);
});

it('shows no flag until asked to', function () {
    $select = CountrySelect::make('country_code')->only(['SE']);

    expect($select->getShowFlags())->toBeFalse()
        ->and($select->getCountries()['SE'])->not->toContain('<img');
});

it('links a published flag file rather than inlining it', function () {
    $select = CountrySelect::make('country_code')->only(['SE'])->showFlags();

    expect($select->getShowFlags())->toBeTrue()
        ->and($select->getCountryFlagUrl('SE'))->toEndWith('/vendor/filament-country-select/flags/se.svg')
        ->and($select->getCountries()['SE'])->toContain('<img')
        ->and($select->getCountries()['SE'])->not->toContain('<svg');
});

it('gives an added entry no flag', function () {
    $select = CountrySelect::make('country_code')->only(['SE'])->add(['XX' => 'Other'])->showFlags();

    expect($select->getCountryFlagUrl('XX'))->toBeNull()
        ->and($select->getCountries()['XX'])->not->toContain('<img');
});

it('does not offer flags on a filter that cannot draw them', function () {
    expect(method_exists(CountrySelectFilter::class, 'showFlags'))->toBeFalse()
        ->and(method_exists(CountrySelectFilter::class, 'phone'))->toBeTrue();
});

it('renders the column without the blade engine', function () {
    $column = CountryColumn::make('country_code');

    expect($column)->toBeInstanceOf(HasEmbeddedView::class)
        ->and(method_exists($column, 'toEmbeddedHtml'))->toBeTrue();
});
