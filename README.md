# Filament Country and Dialling Code Select, Table Column and Filter

For [Filament](https://filamentphp.com). 246 countries, named in 38 languages, with optional flags and optional
international dialling codes for building a phone number input.

The Country Select returns the **ISO 3166-1 alpha-2 country code** (`SE`, `US`, `CA`).
The package has helpers to resolve the dialling code from a country code — see
[Dialling codes](#dialling-codes).


## Installation

```bash
composer require tanthammar/filament-country-select
```

Optionally publish the config and the translations:

```bash
php artisan vendor:publish --tag="filament-country-select-config"
php artisan vendor:publish --tag="filament-country-select-translations"
```

Publish the flags if you want to show them in the select

```bash
php artisan vendor:publish --tag="filament-country-select-flags"
```

# Usage

## Form field

```php
use TantHammar\FilamentCountrySelect\Forms\Components\CountrySelect;

CountrySelect::make('country_code');                // Sweden
CountrySelect::make('country_code')->phone();       // Sweden +46
CountrySelect::make('country_code')->showFlags();   // 🇸🇪 Sweden
```

The selected `$state` is the country code (`SE`, `US`, `CA`). The phone and the flag options only changes the label —
see [Dialling codes](#dialling-codes).

### Options

`only()`, `exclude()` and `add()` work on the select, the column and the filter alike, and each takes an array or
a closure.

```php
CountrySelect::make('country_code')
    ->only(['SE', 'NO', 'DK', 'FI'])
    ->exclude(['FI'])
    ->add(['XX' => 'Other']);
```

`only()` and `exclude()` combine, so excluding narrows what `only()` states. 
Codes are matched case insensitively.

`add()` is for a value that is not a country code — an "Other" option, a "Not stated". Such an entry carries no flag
and no dialling code, and `tryFromName()` **will return null !!!**.

```php
CountrySelect::make('country_code')
    ->only(fn (): array => auth()->user()->team->market_codes);
```

## Table column

```php
use TantHammar\FilamentCountrySelect\Tables\Columns\CountryColumn;

CountryColumn::make('country_code');                // Sweden
CountryColumn::make('country_code')->phone();       // Sweden +46
CountryColumn::make('country_code')->showFlags();   // 🇸🇪 Sweden
```

## Table filter

```php
use TantHammar\FilamentCountrySelect\Tables\Filters\CountrySelectFilter;

CountrySelectFilter::make('country_code');              //search by country name only
CountrySelectFilter::make('country_code')->phone();     //search by dialling code and country name
```

`->phone()` activates search by dialling code and country name. A filter has no `showFlags()`

## Flags

Flags are **off by default** and have to be published before they can be shown:

```bash
php artisan vendor:publish --tag="filament-country-select-flags"
```

```php
CountrySelect::make('country_code')->showFlags();
CountryColumn::make('country_code')->showFlags();
```

### Very small and fast even with rendered flags

Without `->showFlags()` the selects options is a plain `[key => label]` array.

Flags are 32×24 PNGs, about 1 KB each and **_only 272 KB for all flags_**.

Inlining a flag into every option puts the whole flag library into the page. 
The flags are added to the page once for each input in a form. Consider this if used in a repeater. 
Once fetched, the browser caches them across every page.

Even when flags are enabled, the blade renderer is never used, which saves loads of time.


### Change where flags are published

```php
// config/filament-country-select.php
'flags-path' => 'vendor/filament-country-select/flags',
```

### Change flag styling

[stefangabos/world_countries](https://stefangabos.github.io/world_countries) ships the same flags in other sizes
and designs, flat and waving. To use one of those instead, download the set, rename
every file to an uppercase country code (`se.png` becomes `SE.png`), and put them where config `flags-path` points.

Use any format that `<img src"...">` supports, as long as the file names match.

## Dialling codes

`->phone()` 
- Adds the international dialling code to the option label. 
- It changes the label only — the field still stores the ISO code:
- Enables search with dialling code (or country name)
- It takes a closure

```php
CountrySelect::make('country_code')->phone();               // label: "Sweden +46", stores: "SE"
CountrySelect::make('country_code')->phone()->showFlags();  // label: "🇸🇪 Sweden +46", stores: "SE"
CountrySelect::make('country_code')->phone(fn (Get $get): bool => $get('type') === 'phone'); //example closure
```

The search is `%{$searchTerm}%`, so `46` finds Sweden and other countries. Typing `+46` finds Sweden alone.

### Get dialling code from country code

```php
use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;

CountriesEnum::from('SE')->getDialCode();   // '+46'
```

### Building a phone number field
Dialling codes are not unique, like +1 is both US and CA, +7 both RU and KZ...
So, if you want to build a phone input and store the dialling code, you need a country code select which controls the stored value. 
Trying to populate the country code select from a stored dial code would select first found, which could be another country than intended.

Observe that you have to add your preferred validation, this is just an example,

```php
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Utilities\Get;
use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;
use TantHammar\FilamentCountrySelect\Forms\Components\CountrySelect;

FusedGroup::make([
    CountrySelect::make('country_code')
        ->phone()
        ->required()
        ->columnSpan(1),

    Hidden::make('dial_code')
        ->dehydrateStateUsing(
            fn (Get $get): string => CountriesEnum::from($get('country_code'))->getDialCode()
        ),

    TextInput::make('local_phone')
        ->tel()
        ->required()
        ->columnSpan(2),
])->columns(3);
```

## Validation

`CountrySelect` validates against its generated options by default. Dynamically disabled option fails in validation.

### Store country name based on selected country code

`->getName()` returns the country name in current app locale but you can pass the translation you want.

Example to get the country name in English from the selected country code.

```php
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Get;
use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;
use TantHammar\FilamentCountrySelect\Forms\Components\CountrySelect;

CountrySelect::make('country_code')->required();
Hidden::make('country')
    ->dehydrateStateUsing(fn (Get $get): string => CountriesEnum::from($get('country_code'))
    ->getName('en')), //return country name in given translation, leave blank for current locale
```

## Countries Enum

`CountriesEnum` is the entire country list, usable on its own. It backs every ISO 3166-1 alpha-2 country plus `XK`
for Kosovo, which has no ISO code. The list only contains countries that have a dialling code.

```php
use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;

$country = CountriesEnum::from('SE');

$country->getLabel();       // 'Sweden'   the name in the current locale
$country->getName('sv');    // 'Sverige'  the name in a given locale
$country->getDialCode();    // '+46'
$country->getAlpha3();      // 'SWE'
$country->getFlag();        // '<img src="/vendor/.../SE.png" …>'  as an Htmlable
$country->getFlagUrl();     // '/vendor/filament-country-select/flags/SE.png'
$country->getEmojiFlag();   // '🇸🇪'
$country->value;            // 'SE'
```

### `getFlag()` 

Shows a flag outside a Filament component, in your own blade, with no view to render:

```blade
{{ $country->getFlag() }}
{{ $country->getFlag('h-4 w-5 rounded') }}
```

It returns an `HtmlString`, so `{{ }}` renders it rather than escaping it, and it needs the flags to have been
[published](#flags).

On a component, `getCountryLabel()` and `getCountryFlagUrl()` do the same for a stored value, and return `null`
rather than throwing for an [added entry](#shaping-the-list) that is not a country:

```php
$select->getCountryLabel('SE');     // 'Sverige'
$select->getCountryFlagUrl('SE');   // '/vendor/.../SE.png'
$select->getCountryFlagUrl('XX');   // null
```

`getEmojiFlag()` is for places an image cannot go — a plain text mail, a CSV. Whether it draws as a flag or as the
two letters depends on the reader's system: Windows ships no flag emoji unless one has been installed.

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
    // How many options the select renders before the user has to search.
    'options-limit' => 50,

    // Where the flags were published to, relative to the public directory.
    'flags-path' => 'vendor/filament-country-select/flags',

    // The language to name a country in when the current locale has no translation here.
    'fallback-locale' => 'en',
];
```

## Translations

Country names ship in 38 languages and are resolved through Laravel's translator, so they follow the application
locale. The names are the common short forms rather than the ISO official ones — `United Kingdom`, not
`United Kingdom of Great Britain and Northern Ireland`; `Taiwan`, not `Taiwan, Province of China`.

Which language is used follows `app()->getLocale()`, because the names are ordinary translation keys. A locale
this package does not ship falls back to `filament-country-select.fallback-locale`, and then to **English** — not
to the application's own fallback locale, which may well be a language this package does not ship either. A
country is always named, and a translation key never reaches the page.

```php
app()->setLocale('de');  CountriesEnum::SE->getLabel();  // 'Schweden'
app()->setLocale('ja');  CountriesEnum::SE->getLabel();  // 'スウェーデン'
app()->setLocale('vi');  CountriesEnum::SE->getLabel();  // 'Sweden'   not shipped, falls back
```

`getName('es')` asks for one language directly, whatever the application locale is.

**Recognition does not follow the locale.** `tryFromName()` searches every shipped language at once, so a Japanese
country name is resolved while the application runs in Swedish. Stored data rarely agrees with the current locale:

```php
app()->setLocale('sv');
CountriesEnum::tryFromName('スウェーデン');   // SE
```

A test walks `resources/lang` and fails if a language is missing any country, so a language cannot ship half done.

Publish them to change any of it:

```bash
php artisan vendor:publish --tag="filament-country-select-translations"
```

## Render time

A country select carries 246 options, and a form often carries several of them. Livewire rebuilds them on every
round trip, so the package is built to make that cheap.

| per select, 246 countries | time | options html |
| --- | --- | --- |
| default | 1.0 ms | 2.3 KB |
| `->showFlags()->phone()` | 1.9 ms | 60 KB |

What that rests on:

- **No blade anywhere.** Options are built as strings, and `CountryColumn` renders through `HasEmbeddedView`, the
  way Filament's own `ColorColumn` does. Rendering a view per country, per select, cost more than everything else
  in the package put together.
- **Flags are files, not markup.** Inlining a flag per option put the whole flag library in the page, once
  per select. See [Flags](#flags).
- **Markup only when it is needed.** Without flags an option is just the country's name, so the select is an
  ordinary key value select and Filament escapes it as usual.
- **Closures are evaluated once per list**, not once per country. `showFlags()` and `phone()` both accept
  closures, and Filament's parameter injection is not something to run 246 times. They are read again on the next
  build, so a closure that depends on other form state still keeps up.
- **Countries are looked up, not scanned for.** A table column asks for the same country once per row.
- **The selected option is built on its own**, rather than read out of a freshly built list of all 246.

Tests pin the results rather than the intentions: options must stay small, must never contain an inlined `<svg>`
or a `data:` uri, and the default must render as plain text.

## Testing

```bash
composer test
```

## Supported languages

38 languages, each complete for all 246 countries. Names are the common short forms, from
[CLDR](https://cldr.unicode.org).

| | | | |
| --- | --- | --- | --- |
| `ar` Arabic | `bg` Bulgarian | `br` Breton | `cs` Czech |
| `da` Danish | `de` German | `el` Greek | `en` English |
| `eo` Esperanto | `es` Spanish | `et` Estonian | `eu` Basque |
| `fa` Persian | `fi` Finnish | `fr` French | `hr` Croatian |
| `hu` Hungarian | `hy` Armenian | `it` Italian | `ja` Japanese |
| `ko` Korean | `lt` Lithuanian | `nl` Dutch | `no` Norwegian |
| `pl` Polish | `pt` Portuguese | `pt_BR` Portuguese (Brazil) | `ro` Romanian |
| `ru` Russian | `sk` Slovak | `sl` Slovenian | `sr` Serbian |
| `sv` Swedish | `th` Thai | `tr` Turkish | `uk` Ukrainian |
| `zh` Chinese (Simplified) | `zh_TW` Chinese (Traditional) | | |

Publish them to change a name, or add a language of your own:

```bash
php artisan vendor:publish --tag="filament-country-select-translations"
```

A published language is used for display like any other. `tryFromName()` reads the package's own files, so adding
a language there widens what the select displays, not what it recognises.

## Credits
Country names come from [umpirsky/country-list](https://github.com/umpirsky/country-list) (CLDR). Country codes
from [stefangabos/world_countries](https://github.com/stefangabos/world_countries). Flags too, from their
[flat 32x24 set](https://github.com/stefangabos/world_countries/tree/master/data/flags/flat/32x24).

Dialling codes are maintained by hand: neither source ships them.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
