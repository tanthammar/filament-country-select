<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

trait EnumLookups
{
    /**
     * Every country as code => name, in the order the language file lists them.
     *
     * @return array<string, string>
     */
    public static function names(?string $locale = null): array
    {
        static $cache = [];

        $locale ??= app()->getLocale();
        $fallback = config('filament-country-select.fallback-locale', 'en');
        $key = $locale.'|'.$fallback;

        if (isset($cache[$key])) {
            return $cache[$key];
        }

        foreach ([$locale, $fallback, 'en'] as $try) {
            $names = Lang::get('filament-country-select::countries', [], $try, false);

            if (is_array($names) && $names !== []) {
                return $cache[$key] = $names;
            }
        }

        return $cache[$key] = [];
    }

    /** English is the last resort, whatever is configured. */
    public function getName(?string $locale = null): string
    {
        $key = 'filament-country-select::countries.'.$this->value;

        foreach ([$locale, config('filament-country-select.fallback-locale', 'en'), 'en'] as $try) {
            $name = Lang::get($key, [], $try, false);

            if (is_string($name) && $name !== $key) {
                return $name;
            }
        }

        return $this->value;
    }

    /**
     * Matched against every shipped language, not just the current locale. Alpha-2 and alpha-3
     * codes, and the names in resources/aliases.php, resolve too.
     */
    public static function tryFromName(?string $name): ?self
    {
        $name = trim((string) $name);

        if ($name === '') {
            return null;
        }

        if ($country = self::tryFrom(Str::upper($name))) {
            return $country;
        }

        if ($country = self::tryFrom(self::localNameLookup(app()->getLocale())[Str::lower($name)] ?? '')) {
            return $country;
        }

        return self::tryFrom(self::nameLookup()[Str::lower($name)] ?? '');
    }

    /** @return array<string, string> */
    protected static function localNameLookup(string $locale): array
    {
        static $lookups = [];

        if (isset($lookups[$locale])) {
            return $lookups[$locale];
        }

        $names = Lang::get('filament-country-select::countries', [], $locale, false);

        if (! is_array($names)) {
            return $lookups[$locale] = [];
        }

        $lookup = [];

        foreach ($names as $code => $countryName) {
            $lookup[Str::lower(trim($countryName))] ??= $code;
        }

        return $lookups[$locale] = $lookup;
    }

    /** @return array<string, string> */
    protected static function nameLookup(): array
    {
        static $lookup = null;

        if ($lookup !== null) {
            return $lookup;
        }

        $lookup = require __DIR__.'/../../resources/aliases.php';

        foreach (glob(__DIR__.'/../../resources/lang/*/countries.php') ?: [] as $file) {
            foreach (require $file as $code => $countryName) {
                $lookup[Str::lower(trim($countryName))] ??= $code;
            }
        }

        foreach (self::cases() as $country) {
            $lookup[Str::lower($country->getAlpha3())] ??= $country->value;
        }

        return $lookup;
    }
}
