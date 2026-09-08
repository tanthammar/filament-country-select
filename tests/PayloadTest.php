<?php

use TantHammar\FilamentCountrySelect\Forms\Components\CountrySelect;

// Inlining a flag into every option put 3.4 MB into the page per select, so the options must
// stay far below anything of that size.
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

// The selected country is looked up and built on its own. Reading it out of the full option
// list would build all 246 a second time on every render.
it('can build one country on its own to label the selected one', function () {
    $select = CountrySelect::make('a')->showFlags();

    $country = $select->getCountry('SE');
    $built = $select->buildOptions([$country]);

    expect($country['key'])->toBe('SE')
        ->and($built['SE'])->toBe($select->getCountries()['SE'])
        ->and($built['SE'])->toContain('flags/se.svg')
        ->and($select->getCountry('nope'))->toBeNull();
});
