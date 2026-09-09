# Filament Country and Dialling Code Select, Table Column and Filter

For [Filament](https://filamentphp.com). 246 countries, named in 38 languages, with optional flags and optional
international dialling codes for building a phone number input.

The Country Select returns the **ISO 3166-1 alpha-2 country code** (`SE`, `US`, `CA`).
The package has helpers to resolve the dialling code from a country code — see
[Dialling codes](#dialling-codes).
The `->phone()` feature simply adds the dialling code to the select's option label.


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

CountrySelect::make('country_code');                // Sverige
CountrySelect::make('country_code')->phone();       // Sverige +46
CountrySelect::make('country_code')->showFlags();   // 🇸🇪 Sverige
```

Each of them stores `SE`. The dialling code and the flag change what the option looks like, never what is saved —
see [Dialling codes](#dialling-codes) for building a phone number field out of that.

### Table column

```php
use TantHammar\FilamentCountrySelect\Tables\Columns\CountryColumn;

CountryColumn::make('country_code');                // Sverige
CountryColumn::make('country_code')->phone();       // Sverige +46
CountryColumn::make('country_code')->showFlags();   // 🇸🇪 Sverige
```

### Table filter

```php
use TantHammar\FilamentCountrySelect\Tables\Filters\CountrySelectFilter;

CountrySelectFilter::make('country_code');
```

`->phone()` works here too. A filter has no `showFlags()`: `SelectFilter` builds its own inner `Select` and never
allows html on it, so a flag could only ever render as markup.

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
246 country option list at about 2 KB.

Flags are 32×24 PNGs, about 1 KB each and 272 KB for all 246.

They are published as files and referenced with an `<img>` on purpose. Inlining a flag into every option puts the
whole flag library into the page, once per select, and a form with four country selects ships four copies. As
files the browser fetches only the flags it actually paints, and caches them across every page. Base64 data URIs
are worse still — a third larger, they compress badly, and they cannot be cached at all.

Publishing them elsewhere is a config change:

```php
// config/filament-country-select.php
'flags-path' => 'vendor/filament-country-select/flags',
```

### Other flags

[stefangabos/world_countries](https://stefangabos.github.io/world_countries) ships the same flags in other sizes
and designs, flat and waving, from 16×16 up to 128×128. To use one of those instead, download the set, rename
every file to an uppercase country code (`se.png` becomes `SE.png`), and put them where `flags-path` points.

Any format an `<img>` can show works, an SVG set included, as long as the file names match.

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

### Storing a different code

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
CountrySelect::make('country_code')->phone();               // shows "Sverige +46", stores "SE"
CountrySelect::make('country_code')->phone()->showFlags();  // shows "🇸🇪 Sverige +46", stores "SE"
```

Country names are searchable either way. The dialling code is only searchable once `->phone()` is on, because
matching a code that is not on screen would be baffling.

The code is matched anywhere in it, so `46` finds Sweden `+46`, but also Barbados `+1-246` and the British Indian
Ocean Territory `+246`. Typing the `+` narrows it: `+46` finds Sweden alone.

It takes a closure, like any Filament configuration method:

```php
use Filament\Schemas\Components\Utilities\Get;

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
use Filament\Schemas\Components\Utilities\Get;
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

## Validation

`CountrySelect` validates against its generated options by default. Dynamically disabled option fails in validation.

### Store country name based on selected country code

Once the code is validated, another field can be filled from it on save. 
By default it returns the country name in current app locale but you can optionally pass the translation you want.

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

A visible `TextInput` fills the same way. Making `country_code` required and valid is what keeps `from()` safe
here, and that is yours to do.

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
$country->getFlag();        // '<img src="/vendor/.../SE.png" …>'  as an Htmlable
$country->getFlagUrl();     // '/vendor/filament-country-select/flags/SE.png'
$country->getEmojiFlag();   // '🇸🇪'
$country->value;            // 'SE'
```

`getFlag()` shows a flag outside a Filament component, in your own blade, with no view to render:

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
