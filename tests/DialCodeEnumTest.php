<?php

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;
use TantHammar\FilamentCountrySelect\Enums\DialCodeEnum;

// The two enums declare their cases separately, so they can drift apart by hand.
it('declares exactly the same countries as the country enum', function () {
    $countries = array_column(CountriesEnum::cases(), 'value');
    $dialCodes = array_column(DialCodeEnum::cases(), 'value');

    expect($dialCodes)->toBe($countries);
});

it('labels a country with its dialling code', function () {
    app()->setLocale('en');
    expect(DialCodeEnum::SE->getLabel())->toBe('Sweden +46');

    app()->setLocale('sv');
    expect(DialCodeEnum::SE->getLabel())->toBe('Sverige +46')
        ->and(CountriesEnum::SE->getLabel())->toBe('Sverige');
});

// `self` inside the shared trait has to resolve to the enum using it, not to the other one.
it('resolves names to its own type', function () {
    expect(DialCodeEnum::tryFromName('Sverige'))->toBeInstanceOf(DialCodeEnum::class)
        ->and(DialCodeEnum::tryFromName('SWE'))->toBeInstanceOf(DialCodeEnum::class)
        ->and(CountriesEnum::tryFromName('Sverige'))->toBeInstanceOf(CountriesEnum::class)
        ->and(DialCodeEnum::tryFromName('Bavaria'))->toBeNull();
});

it('shares the country data through the traits', function (string $enum) {
    expect($enum::SE->getDialCode())->toBe('+46')
        ->and($enum::SE->getAlpha3())->toBe('SWE')
        ->and($enum::SE->getEmojiFlag())->toBe('🇸🇪')
        ->and((string) $enum::SE->getFlag())->toContain('flags/SE.png')
        ->and($enum::SE->getName('es'))->toBe('Suecia')
        ->and($enum::names())->toHaveCount(246);
})->with([CountriesEnum::class, DialCodeEnum::class]);

it('implements the filament enum interfaces', function () {
    expect(DialCodeEnum::SE)->toBeInstanceOf(HasLabel::class)
        ->and(DialCodeEnum::SE)->toBeInstanceOf(HasIcon::class)
        ->and((string) DialCodeEnum::SE->getIcon())->toContain('flags/SE.png');
});
