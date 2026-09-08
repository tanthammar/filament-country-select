<?php

use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;

it('resolves a country from a name in any shipped language', function (string $name, string $expected) {
    expect(CountriesEnum::tryFromName($name)?->value)->toBe($expected);
})->with([
    'swedish' => ['Sverige', 'SE'],
    'english' => ['Sweden', 'SE'],
    'spanish' => ['Suecia', 'SE'],
    'polish' => ['Szwecja', 'SE'],
    'german' => ['Deutschland', 'DE'],
    'finnish' => ['Suomi', 'FI'],
    'arabic' => ['السويد', 'SE'],
    'chinese' => ['瑞典', 'SE'],
    'greek' => ['Ελλάδα', 'GR'],
]);

it('resolves a country from an alternative or former name', function (string $name, string $expected) {
    expect(CountriesEnum::tryFromName($name)?->value)->toBe($expected);
})->with([
    'former name' => ['Czech Republic', 'CZ'],
    'english exonym' => ['Ivory Coast', 'CI'],
    'renamed' => ['Macedonia', 'MK'],
    'renamed again' => ['Burma', 'MM'],
    'abbreviation' => ['UK', 'GB'],
]);

it('resolves a country from its codes', function (string $name, string $expected) {
    expect(CountriesEnum::tryFromName($name)?->value)->toBe($expected);
})->with([
    'alpha2' => ['SE', 'SE'],
    'lowercase alpha2' => ['se', 'SE'],
    'alpha3' => ['SWE', 'SE'],
    'lowercase alpha3' => ['swe', 'SE'],
]);

it('ignores surrounding whitespace and casing', function (string $name) {
    expect(CountriesEnum::tryFromName($name)?->value)->toBe('SE');
})->with(['  Sverige  ', "\tSverige\n", 'sverige', 'SVERIGE']);

it('resolves nothing for what is not a country', function (?string $name) {
    expect(CountriesEnum::tryFromName($name))->toBeNull();
})->with([
    'a constituent nation' => 'England',
    'a region' => 'Bavaria',
    'a state' => 'Texas',
    'a city' => 'Paris',
    'a misspelling' => 'Swedne',
    'nonsense' => 'not a country',
    'empty' => '',
    'blank' => ' ',
    'null' => null,
]);

// Holland is what Danish and Estonian call the Netherlands, so it resolves from those
// translations rather than from a guess about a region standing in for its country.
it('resolves a name that another language uses for the whole country', function () {
    expect(CountriesEnum::tryFromName('Holland')?->value)->toBe('NL');
});

it('never resolves a name to a country it does not have', function () {
    $codes = collect(CountriesEnum::cases())->map(fn (CountriesEnum $country): string => $country->value);

    $dangling = collect(require __DIR__.'/../resources/aliases.php')
        ->reject(fn (string $code): bool => $codes->contains($code));

    expect($dangling)->toBeEmpty();
});
