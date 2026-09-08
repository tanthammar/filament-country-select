<?php

use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;
use TantHammar\FilamentCountrySelect\Forms\Components\CountrySelect;

// 246 flags cost 3.4 MB inlined and 60 KB as <img> tags pointing at the published files.
it('renders an ordinary key value select until a flag is asked for', function () {
    $select = CountrySelect::make('a')->phone();
    $options = $select->getCountries();

    expect($select->isHtmlAllowed())->toBeFalse()
        ->and($options['SE'])->toBe('Sweden +46')
        ->and(strlen(implode('', $options)))->toBeLessThan(10_000);
});

it('renders markup only to draw a flag', function () {
    $select = CountrySelect::make('b')->showFlags();
    $options = $select->getCountries();

    expect($select->isHtmlAllowed())->toBeTrue()
        ->and(strlen(implode('', $options)))->toBeLessThan(150_000);
});

it('never inlines an svg into an option', function () {
    $options = implode('', CountrySelect::make('c')->showFlags()->getCountries());

    expect($options)->not->toContain('<svg')
        ->and($options)->not->toContain('data:image')
        ->and($options)->toContain('<img');
});

// Reading the label out of the full option list would build all 246 a second time per render.
it('can build one country on its own to label the selected one', function () {
    $select = CountrySelect::make('a')->showFlags();

    $country = $select->getCountry('SE');
    $built = $select->buildOptions([$country]);

    expect($country['key'])->toBe('SE')
        ->and($built['SE'])->toBe($select->getCountries()['SE'])
        ->and($built['SE'])->toContain('flags/SE.png')
        ->and($select->getCountry('nope'))->toBeNull();
});

it('evaluates a closure once per build, not once per country', function () {
    $flagCalls = 0;
    $phoneCalls = 0;

    $select = CountrySelect::make('a')
        ->showFlags(function () use (&$flagCalls): bool {
            $flagCalls++;

            return true;
        })
        ->phone(function () use (&$phoneCalls): bool {
            $phoneCalls++;

            return true;
        });

    expect($select->getCountries())->toHaveCount(246)
        ->and($flagCalls)->toBe(1)
        ->and($phoneCalls)->toBe(1);

    $select->getCountries();

    expect($flagCalls)->toBe(2)
        ->and($phoneCalls)->toBe(2);
});

it('reads a closure again on the next build so it keeps up with form state', function () {
    $showFlags = false;

    $select = CountrySelect::make('a')->showFlags(function () use (&$showFlags): bool {
        return $showFlags;
    });

    expect($select->getCountries()['SE'])->toBe('Sweden');

    $showFlags = true;

    expect($select->getCountries()['SE'])->toContain('<img');
});

// 246 flags cost 3.4 MB inlined and 60 KB as <img> tags pointing at the published files.
it('returns a flag as an img tag, never as an inlined svg', function () {
    $flag = (string) CountriesEnum::SE->getFlag();

    expect($flag)->toStartWith('<img ')
        ->and($flag)->toContain('src="'.CountriesEnum::SE->getFlagUrl().'"')
        ->and($flag)->not->toContain('<svg')
        ->and($flag)->not->toContain('data:image')
        ->and($flag)->not->toContain('<path');
});

it('builds the same flag url from the enum and from a component', function () {
    $select = CountrySelect::make('a')->showFlags();

    expect($select->getCountryFlagUrl('SE'))->toBe(CountriesEnum::SE->getFlagUrl())
        ->and($select->getCountries()['SE'])->toContain(CountriesEnum::SE->getFlagUrl());
});
