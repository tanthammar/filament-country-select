<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Illuminate\Support\Str;
use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;

trait HasCountryData
{
    /** @var array<int, array{key: string, iso_code: ?string, label: string, dial_code: ?string}>|null */
    protected ?array $countriesData = null;

    /** @var array<string, array{key: string, iso_code: ?string, label: string, dial_code: ?string}>|null */
    protected ?array $countryIndex = null;

    protected ?string $flagsPath = null;

    /**
     * The countries to offer, shaped by only(), exclude() and add().
     *
     * A row's key is what gets stored, its iso_code is the country it draws a flag from. They
     * are the same for a country, and an added entry has no iso_code at all.
     *
     * @return array<int, array{key: string, iso_code: ?string, label: string, dial_code: ?string}>
     */
    public function getCountriesData(): array
    {
        if ($this->countriesData !== null) {
            return $this->countriesData;
        }

        $only = $this->listOption('getOnly');
        $exclude = $this->listOption('getExclude');

        $countries = collect(CountriesEnum::cases())
            ->when($only !== [], fn ($countries) => $countries->filter(
                fn (CountriesEnum $country): bool => in_array($country->value, $only, true)
            ))
            ->reject(fn (CountriesEnum $country): bool => in_array($country->value, $exclude, true))
            ->map(fn (CountriesEnum $country): array => [
                'key' => $country->value,
                'iso_code' => $country->value,
                'label' => $country->getLabel(),
                'dial_code' => $country->getDialCode(),
            ])
            ->values();

        foreach ($this->listOption('getAdd') as $key => $label) {
            $countries->push([
                'key' => $key,
                'iso_code' => null,
                'label' => $label,
                'dial_code' => null,
            ]);
        }

        $this->countryIndex = null;

        return $this->countriesData = $countries->all();
    }

    /**
     * Looked up by key rather than scanned for. A table column asks for the same country once
     * per row, and scanning the whole list each time is a lot of work to do per row.
     *
     * @return array{key: string, iso_code: ?string, label: string, dial_code: ?string}|null
     */
    public function getCountry(?string $key): ?array
    {
        $this->countryIndex ??= array_column($this->getCountriesData(), null, 'key');

        return $this->countryIndex[Str::upper((string) $key)] ?? null;
    }

    /** The country name for a stored value, dialling code appended when asked for. */
    public function getCountryLabel(?string $key): ?string
    {
        $country = $this->getCountry($key);

        if ($country === null) {
            return null;
        }

        return $this->wantsDialCode() && $country['dial_code'] !== null
            ? $country['label'].' '.$country['dial_code']
            : $country['label'];
    }

    /** The flag alias for a stored value, null when it draws no flag. */
    public function getCountryFlag(?string $key): ?string
    {
        $isoCode = $this->getCountry($key)['iso_code'] ?? null;

        return $isoCode === null ? null : Str::lower($isoCode);
    }

    /** The published url of a country's flag, null when it draws no flag. */
    public function getCountryFlagUrl(?string $key): ?string
    {
        return $this->flagUrl($this->getCountry($key)['iso_code'] ?? null);
    }

    /** The published url of a flag, from an ISO code that is already known. */
    protected function flagUrl(?string $isoCode): ?string
    {
        if ($isoCode === null) {
            return null;
        }

        $this->flagsPath ??= trim((string) config('filament-country-select.flags-path'), '/');

        return asset($this->flagsPath.'/'.Str::lower($isoCode).'.svg');
    }

    protected function wantsDialCode(): bool
    {
        return method_exists($this, 'getPhone') && $this->getPhone();
    }

    /** @return array<array-key, string> */
    protected function listOption(string $method): array
    {
        return method_exists($this, $method) ? $this->{$method}() : [];
    }
}
