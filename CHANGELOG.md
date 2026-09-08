# Changelog

All notable changes to `filament-country-select` will be documented in this file.

## 1.0.0

Initial release.

### Countries

- The stored value is the ISO 3166-1 alpha-2 country code, never the dialling code
- `US`/`CA` and `RU`/`KZ` are separate countries again, each with its own name and flag
- 246 countries, up from 210, with alpha-3 codes and a Kosovo entry
- Country names in 38 languages, from CLDR, falling back to English rather than to the
  application's own fallback locale
- `CountriesEnum::tryFromName()` resolves a country from a name in any shipped language, an
  alpha-2 or alpha-3 code, or a former name

### Components

- `->phone()` appends the dialling code to the option label and makes it searchable
- `->showFlags()`, off by default, draws a flag from the published files
- `->only()`, `->exclude()` and `->add()` shape the list
- The table column renders through `HasEmbeddedView` rather than a blade view

### Render time

- Flags are published files behind an `<img>`, not inlined SVG. A full option list is 60 KB
  rather than 3.4 MB, and 3.5 KB with flags off
- Options are built as strings, so no blade view is rendered per country
- `showFlags()` and `phone()` closures are evaluated once per list, not once per country
