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

Store `SE`, show 🇸🇪 Sverige.

Without flags:

```php
CountrySelect::make('country_code')->flags(false);
```

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

`->phone()` and `->flags(false)` work here too, though a filter renders plain text, so it shows no flag.

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
