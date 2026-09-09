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

// Territories with no telephone service are left out, so a dialling code is never null.
it('leaves out territories that cannot be reached', function () {
    foreach (['BV', 'HM', 'TF', 'UM'] as $unreachable) {
        expect(CountriesEnum::tryFrom($unreachable))->toBeNull();
    }
});

it('has an alpha3 code and a flag emoji for every country', function () {
    foreach (CountriesEnum::cases() as $country) {
        expect($country->getAlpha3())->toMatch('/^[A-Z]{3}$/')
            ->and(mb_strlen($country->getEmojiFlag()))->toBe(2);
    }
});

it('has a flag for every country', function () {
    $missing = collect(CountriesEnum::cases())
        ->reject(fn (CountriesEnum $country): bool => file_exists(
            __DIR__.'/../resources/flags/'.$country->value.'.png'
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
        ->and($country->getEmojiFlag())->toBe('🇸🇪')
        ->and((string) $country->getFlag())->toContain('flags/SE.png')
        ->and($country->getFlagUrl())->toEndWith('/SE.png');
});

it('falls back to the language configured for it', function () {
    config()->set('app.fallback_locale', 'vi');
    config()->set('filament-country-select.fallback-locale', 'sv');
    app()->setLocale('vi');

    expect(CountriesEnum::SE->getLabel())->toBe('Sverige');
});

// A locale the package does not ship must not put a translation key on the page.
it('falls back to english for a language it does not ship', function () {
    config()->set('filament-country-select.fallback-locale', 'vi');
    config()->set('app.fallback_locale', 'vi');
    app()->setLocale('vi');

    expect(CountriesEnum::SE->getLabel())->toBe('Sweden')
        ->and(CountriesEnum::SE->getName('vi'))->toBe('Sweden')
        ->and(CountriesEnum::SE->getName())->not->toContain('filament-country-select');
});

it('still uses a language it does ship', function () {
    config()->set('app.fallback_locale', 'vi');
    app()->setLocale('de');

    expect(CountriesEnum::SE->getLabel())->toBe('Schweden')
        ->and(CountriesEnum::SE->getName('ja'))->toBe('スウェーデン');
});

it('falls back to its own language, not the application one', function () {
    config()->set('app.fallback_locale', 'de');
    config()->set('filament-country-select.fallback-locale', 'en');
    app()->setLocale('vi');

    expect(CountriesEnum::SE->getLabel())->toBe('Sweden');
});

// +379 is assigned to the Vatican but was never put into service; its numbers are Italian.
it('gives the vatican the dialling code its numbers actually use', function () {
    expect(CountriesEnum::VA->getDialCode())->toBe('+39');
});
