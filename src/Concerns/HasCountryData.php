<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use BackedEnum;
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

        $countries = [];

        foreach (CountriesEnum::names() as $code => $label) {
            $country = CountriesEnum::tryFrom($code);

            if ($country === null) {
                continue;
            }

            if ($only !== [] && ! in_array($code, $only, true)) {
                continue;
            }

            if (in_array($code, $exclude, true)) {
                continue;
            }

            $countries[] = [
                'key' => $code,
                'iso_code' => $code,
                'label' => $label,
                'dial_code' => $country->getDialCode(),
            ];
        }

        $countries = $this->sortPutFirst($countries);

        foreach ($this->getAdd() as $key => $label) {
            $countries[] = [
                'key' => $key,
                'iso_code' => null,
                'label' => $label,
                'dial_code' => null,
            ];
        }

        $this->countryIndex = null;

        return $this->countriesData = $countries;
    }

    /**
     * @return array{key: string, iso_code: ?string, label: string, dial_code: ?string}|null
     */
    public function getCountry(BackedEnum|string|null $key): ?array
    {
        $this->countryIndex ??= array_column($this->getCountriesData(), null, 'key');

        $key = (string) ($key instanceof BackedEnum ? $key->value : $key);

        return $this->countryIndex[$key] ?? $this->countryIndex[Str::upper($key)] ?? null;
    }

    public function getCountryLabel(BackedEnum|string|null $key): ?string
    {
        $country = $this->getCountry($key);

        if ($country === null) {
            return null;
        }

        return $this->getPhone() && $country['dial_code'] !== null
            ? $country['label'].' '.$country['dial_code']
            : $country['label'];
    }

    /** Null for an added entry, which is not a country and draws no flag. */
    public function getCountryFlagUrl(BackedEnum|string|null $key): ?string
    {
        $isoCode = $this->getCountry($key)['iso_code'] ?? null;

        return $isoCode === null ? null : CountriesEnum::from($isoCode)->getFlagUrl();
    }

    /**
     * @param  array<int, array{key: string, iso_code: ?string, label: string, dial_code: ?string}>  $countries
     * @return array<int, array{key: string, iso_code: ?string, label: string, dial_code: ?string}>
     */
    protected function sortPutFirst(array $countries): array
    {
        $putFirst = $this->getPutFirst();

        if ($putFirst === []) {
            return $countries;
        }

        $remaining = array_column($countries, null, 'key');
        $pinned = [];

        foreach ($putFirst as $code) {
            if (isset($remaining[$code])) {
                $pinned[] = $remaining[$code];
                unset($remaining[$code]);
            }
        }

        return [...$pinned, ...array_values($remaining)];
    }

    /**
     * @return array<int, array{key: string, iso_code: ?string, label: string, dial_code: ?string}>
     */
    protected function matching(string $search): array
    {
        $searchesDialCodes = $this->getPhone();
        $code = mb_strtoupper(trim($search));

        $exact = [];
        $rest = [];

        foreach ($this->getCountriesData() as $country) {
            if (mb_strtoupper($country['key']) === $code) {
                $exact[] = $country;

                continue;
            }

            if (mb_stripos($country['label'], $search) !== false
                || mb_stripos($country['key'], $search) !== false
                || ($searchesDialCodes && $country['dial_code'] !== null && str_contains($country['dial_code'], $search))) {
                $rest[] = $country;
            }
        }

        return [...$exact, ...$rest];
    }
}
