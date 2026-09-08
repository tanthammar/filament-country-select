# Filament Country Select

A country select form field, table column and table filter for [Filament](https://filamentphp.com), with flags,
translated country names, and optional international dialling codes.

The value is always the **ISO 3166-1 alpha-2 country code** (`SE`, `US`, `CA`), never the dialling code. A dialling
code does not identify a country — the United States and Canada share `+1`, Russia and Kazakhstan share `+7` — so
storing one loses information that cannot be recovered. `->phone()` adds the dialling code to the label only.

## Installation

```bash
composer require tanthammar/filament-country-select
```

Optionally publish the config and the translations:

```bash
php artisan vendor:publish --tag="filament-country-select-config"
php artisan vendor:publish --tag="filament-country-select-translations"
```

## Usage

### Form field

```php
use TantHammar\FilamentCountrySelect\Forms\Components\CountrySelect;

CountrySelect::make('country_code');
```

Stores `SE`, shows `Sverige`.

### Table column

```php
use TantHammar\FilamentCountrySelect\Tables\Columns\CountryColumn;

CountryColumn::make('country_code');          // 🇸🇪 Sverige
CountryColumn::make('country_code')->phone(); // 🇸🇪 Sverige +46
```

### Table filter

```php
use TantHammar\FilamentCountrySelect\Tables\Filters\CountrySelectFilter;

CountrySelectFilter::make('country_code');
```

`->phone()` works here too. A filter renders plain text, so it never shows a flag.

## Flags

Flags are **off by default** and have to be published before they can be shown:

```bash
php artisan vendor:publish --tag="filament-country-select-flags"
```

```php
CountrySelect::make('country_code')->showFlags();
CountryColumn::make('country_code')->showFlags();
```

Until a flag is asked for the select is an ordinary key value select — an option is just the country's name, so
Filament escapes and renders it like any other. Markup is only produced to draw a flag, which keeps the whole
246 country option list at about 3.5 KB.

Flags are published as files and referenced with an `<img>` on purpose. Inlining an SVG into every option puts the
whole flag library into the page: 3.4 MB per select, 1.1 MB even gzipped, and a form with four country selects
ships four copies. As files the browser fetches only the flags it actually paints, and caches them across every
page. Base64 data URIs are worse still — a third larger, they compress badly, and they cannot be cached at all.

Publishing them elsewhere is a config change:

```php
// config/filament-country-select.php
'flags-path' => 'vendor/filament-country-select/flags',
```

## Shaping the list

`only()`, `exclude()` and `add()` work on the select, the column and the filter alike, and each takes an array or
a closure.

```php
CountrySelect::make('country_code')
    ->only(['SE', 'NO', 'DK', 'FI'])          // just these, in the order the package lists them
    ->exclude(['FI'])                          // everything but these
    ->add(['XX' => 'Other']);                  // entries of your own, after the countries
```

`only()` and `exclude()` combine, so excluding narrows what `only()` offered. Codes are matched case
insensitively.

`add()` is for a value that is not a country — an "Other" option, a "Not stated". Such an entry carries no flag
and no dialling code, and `tryFromName()` will never return one, because it is not in the country list.

```php
CountrySelect::make('country_code')
    ->only(fn (): array => auth()->user()->team->market_codes);
```

### There is no map()

The stored value is always the ISO code. A form that has to read or write something else — a legacy column
holding `UK`, an API that wants its own codes — can say so where it happens, with Filament's own methods:

```php
CountrySelect::make('country')
    ->formatStateUsing(fn (?string $state): ?string => $state === 'UK' ? 'GB' : $state)
    ->dehydrateStateUsing(fn (?string $state): ?string => $state === 'GB' ? 'UK' : $state);
```

That keeps the exception at the one call site that needs it, instead of letting every component in the
application disagree about what a country code is.

## Dialling codes

`->phone()` adds the international dialling code to every option label. It changes the label only — the field still
stores the ISO code:

```php
CountrySelect::make('country_code')->phone();   // shows "🇸🇪 Sverige +46", stores "SE"
```

Searching then matches the dialling code as well as the name, so typing `46` finds Sweden.

It takes a closure, like any Filament configuration method:

```php
CountrySelect::make('country_code')->phone(fn (Get $get): bool => $get('type') === 'phone');
```

### Why the value stays the ISO code

A dialling code does not identify a country. `+1` is the United States *and* Canada, `+7` is Russia *and*
Kazakhstan. Storing `+1` loses which one the user picked, and libphonenumber needs a region code, not a dialling
code, to format or validate a number. So the ISO code is stored and the dialling code is derived from it:

```php
use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;

CountriesEnum::from('SE')->getDialCode();   // '+46'
```

### Building a phone number field

Fuse the select with a number input and combine the two on save:

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;
use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;
use TantHammar\FilamentCountrySelect\Forms\Components\CountrySelect;

FusedGroup::make([
    CountrySelect::make('country_code')
        ->phone()
        ->default('SE')
        ->live()
        ->dehydrated(false)
        ->columnSpan(1),
    TextInput::make('phone')
        ->tel()
        ->columnSpan(2)
        ->dehydrateStateUsing(fn (Get $get, ?string $state): ?string => filled($state)
            ? CountriesEnum::from($get('country_code'))->getDialCode().ltrim($state, '0')
            : null),
])->columns(3);
```

## The countries

`CountriesEnum` is the whole country list, usable on its own. It backs every ISO 3166-1 alpha-2 country plus `XK`
for Kosovo, which has no ISO code but is widely used, and leaves out the four territories with no telephone
service of their own, so a dialling code is never null.

```php
use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;

$country = CountriesEnum::from('SE');

$country->getLabel();       // 'Sverige'   the name in the current locale
$country->getName('es');    // 'Suecia'    the name in a given locale
$country->getDialCode();    // '+46'
$country->getAlpha3();      // 'SWE'
$country->getFlag();        // '🇸🇪'       regional indicator symbols, no asset needed
$country->getFlagAlias();   // 'se'        the svg the Filament components render
$country->value;            // 'SE'
```

`getFlag()` is for places a Blade icon cannot go — mail, notifications, exports, plain text. The Filament
components use the bundled SVGs instead, which also render on Windows, where flag emoji have no glyphs.

### Resolving a country from a name

`tryFromName()` answers "which country is this?" for a name a person typed or a system stored. It matches, after
trimming and folding case: an alpha-2 code, an alpha-3 code, a name in **any** of the 38 shipped languages, and a
short list of other names for the country itself — former official names, English exonyms and abbreviations.

```php
CountriesEnum::tryFromName('Sverige');        // SE
CountriesEnum::tryFromName('Szwecja');        // SE   any shipped language, not just the current one
CountriesEnum::tryFromName('Deutschland');    // DE
CountriesEnum::tryFromName('Ivory Coast');    // CI   English exonym for Côte d'Ivoire
CountriesEnum::tryFromName('Czech Republic'); // CZ   the former official name
CountriesEnum::tryFromName('SWE');            // SE
CountriesEnum::tryFromName('Bavaria');        // null
```

Recognition is deliberately wider than display: stored data rarely agrees with the current locale, so every
language is searched, whichever one the application is running in. That makes it useful for importing addresses,
normalising a legacy column, or accepting a country from an API.

It will not guess. A region, a state, a city or a constituent nation is not the country it sits in, and neither is
a misspelling, so those resolve to `null` rather than to a plausible neighbour. `Holland` is the exception that
proves the rule: it resolves to `NL` because it is what Danish and Estonian actually call the country, not because
a region was mapped onto one.

## Configuration

```php
// config/filament-country-select.php
return [
    'options-limit' => 100,   // how many options the select renders before searching
];
```

## Translations

Country names ship in 38 languages and are resolved through Laravel's translator, so they follow the application
locale. The names are the common short forms rather than the ISO official ones — `United Kingdom`, not
`United Kingdom of Great Britain and Northern Ireland`; `Taiwan`, not `Taiwan, Province of China`.

A test walks `resources/lang` and fails if a language is missing any country, so a language cannot ship half done.

Publish them to change any of it:

```bash
php artisan vendor:publish --tag="filament-country-select-translations"
```

## Testing

```bash
composer test
```

## Credits

Derived from [tapp/filament-country-code-field](https://github.com/TappNetwork/filament-country-code-field) by
Tapp Network. Rewritten to key on ISO codes, to separate countries that share a dialling code, and to add
translations.

Country names come from [umpirsky/country-list](https://github.com/umpirsky/country-list) (CLDR), ISO codes from
[stefangabos/world_countries](https://github.com/stefangabos/world_countries).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
