# Changelog

All notable changes to `filament-country-select` will be documented in this file.

## 1.0.0

Initial release, rewritten from `tapp/filament-country-code-field`:

- Options are keyed by ISO 3166-1 alpha-2 code instead of dialling code
- `US`/`CA` and `RU`/`KZ` are separate countries again, each with its own flag and name
- `->phone()` appends the dialling code to the label rather than changing the stored value
- `CountriesEnum::getDialCode()` replaces `getCountryCode()`, which returned a dialling code
- Swedish and Spanish country names added, `namibia` and the Dominican Republic dialling code fixed
- Table column shows the country name rather than the raw stored value
