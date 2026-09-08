<?php

use TantHammar\FilamentCountrySelect\Forms\Components\CountrySelect;
use TantHammar\FilamentCountrySelect\Tables\Filters\CountrySelectFilter;

it('keys its options by iso code', function () {
    $options = CountrySelectFilter::make('country_code')->getCountries();

    expect(array_keys($options))->toContain('SE', 'US', 'CA', 'RU', 'KZ')
        ->and($options)->toHaveCount(246);
});

it('names countries without a dialling code by default', function () {
    $filter = CountrySelectFilter::make('country_code');

    expect($filter->getPhone())->toBeFalse()
        ->and($filter->getCountries()['SE'])->toBe('Sweden');
});

it('appends the dialling code when asked to', function () {
    $filter = CountrySelectFilter::make('country_code')->phone();

    expect($filter->getPhone())->toBeTrue()
        ->and($filter->getCountries()['SE'])->toBe('Sweden +46')
        ->and($filter->getCountries()['US'])->toBe('United States +1')
        ->and($filter->getCountries()['CA'])->toBe('Canada +1');
});

it('translates the option labels', function () {
    app()->setLocale('sv');

    expect(CountrySelectFilter::make('country_code')->getCountries()['SE'])->toBe('Sverige');

    app()->setLocale('es');

    expect(CountrySelectFilter::make('country_code')->getCountries()['SE'])->toBe('Suecia');
});

it('resolves a country label from an iso code', function () {
    $select = CountrySelect::make('country_code');

    expect($select->getCountryLabel('SE'))->toBe('Sweden')
        ->and($select->getCountryLabel('se'))->toBe('Sweden')
        ->and($select->getCountryLabel('nope'))->toBeNull()
        ->and($select->phone()->getCountryLabel('SE'))->toBe('Sweden +46');
});

it('searches case insensitively in a non latin alphabet', function () {
    app()->setLocale('el');

    $select = CountrySelect::make('country_code');

    expect($select->getSearchResults('ελλάδα'))->toBe(['GR' => 'Ελλάδα'])
        ->and($select->getSearchResults('Ελλάδα'))->toBe(['GR' => 'Ελλάδα']);
});

it('searches the dialling code when it shows one', function () {
    expect(CountrySelect::make('country_code')->phone()->getSearchResults('+46'))->toBe(['SE' => 'Sweden +46'])
        ->and(CountrySelect::make('country_code')->getSearchResults('+46'))->toBe([]);
});
