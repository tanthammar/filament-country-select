<?php

use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;

it('uses the uppercase iso 3166-1 alpha-2 code as its value', function () {
    foreach (CountriesEnum::cases() as $country) {
        expect($country->value)->toBe(mb_strtoupper($country->value))
            ->and($country->value)->toMatch('/^[A-Z]{2}$/');
    }
});

// A dialling code is not unique, so countries sharing one must stay separate entries.
it('keeps countries that share a dialling code apart', function () {
    expect(CountriesEnum::US->getDialCode())->toBe('+1')
        ->and(CountriesEnum::CA->getDialCode())->toBe('+1')
        ->and(CountriesEnum::RU->getDialCode())->toBe('+7')
        ->and(CountriesEnum::KZ->getDialCode())->toBe('+7');
});

it('has a dialling code for every country', function () {
    foreach (CountriesEnum::cases() as $country) {
        expect($country->getDialCode())->toMatch('/^\+[0-9-]+$/');
    }
});

it('covers every iso 3166-1 country that can be reached', function () {
    expect(CountriesEnum::cases())->toHaveCount(246)
        ->and(CountriesEnum::tryFrom('AX'))->not->toBeNull()
        ->and(CountriesEnum::tryFrom('PR'))->not->toBeNull()
        ->and(CountriesEnum::tryFrom('XK'))->not->toBeNull();
});

// Uninhabited territories with no telephone service of their own are left out, so that a
// dialling code never has to be null.
it('leaves out territories that cannot be reached', function () {
    foreach (['BV', 'HM', 'TF', 'UM'] as $unreachable) {
        expect(CountriesEnum::tryFrom($unreachable))->toBeNull();
    }
});

it('has an alpha3 code and a flag emoji for every country', function () {
    foreach (CountriesEnum::cases() as $country) {
        expect($country->getAlpha3())->toMatch('/^[A-Z]{3}$/')
            ->and(mb_strlen($country->getFlag()))->toBe(2);
    }
});

it('has a flag for every country', function () {
    $missing = collect(CountriesEnum::cases())
        ->reject(fn (CountriesEnum $country): bool => file_exists(
            __DIR__.'/../resources/svg/'.mb_strtolower($country->value).'.svg'
        ))
        ->map(fn (CountriesEnum $country): string => $country->value);

    expect($missing)->toBeEmpty();
});

it('translates every country in every language it ships', function () {
    $locales = collect(scandir(__DIR__.'/../resources/lang'))
        ->reject(fn (string $entry): bool => str_starts_with($entry, '.'));

    expect($locales)->not->toBeEmpty();

    foreach ($locales as $locale) {
        $names = require __DIR__.'/../resources/lang/'.$locale.'/countries.php';

        $missing = collect(CountriesEnum::cases())
            ->reject(fn (CountriesEnum $country): bool => filled($names[$country->value] ?? null))
            ->map(fn (CountriesEnum $country): string => $country->value);

        expect($missing)->toBeEmpty("{$locale} is missing names");
    }
});

// The values the README documents, so the examples cannot drift from the code.
it('exposes a country through the documented getters', function () {
    app()->setLocale('sv');

    $country = CountriesEnum::from('SE');

    expect($country->value)->toBe('SE')
        ->and($country->getLabel())->toBe('Sverige')
        ->and($country->getName('es'))->toBe('Suecia')
        ->and($country->getName())->toBe('Sverige')
        ->and($country->getDialCode())->toBe('+46')
        ->and($country->getAlpha3())->toBe('SWE')
        ->and($country->getFlag())->toBe('🇸🇪')
        ->and($country->getFlagAlias())->toBe('se');
});
