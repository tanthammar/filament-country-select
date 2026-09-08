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

    /**
     * key is what gets stored, iso_code is what draws a flag. An added entry has no iso_code.
     *
     * @return array<int, array{key: string, iso_code: ?string, label: string, dial_code: ?string}>
     */
    public function getCountriesData(): array
    {
        if ($this->countriesData !== null) {
            return $this->countriesData;
        }

        $only = $this->getOnly();
        $exclude = $this->getExclude();

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

        foreach ($this->getAdd() as $key => $label) {
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
     * @return array{key: string, iso_code: ?string, label: string, dial_code: ?string}|null
     */
    public function getCountry(?string $key): ?array
    {
        $this->countryIndex ??= array_column($this->getCountriesData(), null, 'key');

        return $this->countryIndex[Str::upper((string) $key)] ?? null;
    }

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

    /** Null for an added entry, which is not a country and draws no flag. */
    public function getCountryFlagUrl(?string $key): ?string
    {
        $isoCode = $this->getCountry($key)['iso_code'] ?? null;

        return $isoCode === null ? null : CountriesEnum::from($isoCode)->getFlagUrl();
    }

    protected function wantsDialCode(): bool
    {
        return $this->getPhone();
    }
}
