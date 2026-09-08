<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;

trait HasCountryData
{
    public function getCountriesData(): array
    {
        return collect(CountriesEnum::cases())->map(fn (CountriesEnum $country): array => [
            'label' => $country->getLabel(),
            'dial_code' => $country->getDialCode(),
            'iso_code' => $country->value,
        ])->toArray();
    }

    /** The translated country name for an ISO code, dialling code appended when asked for. */
    public function getCountryLabel(?string $isoCode): ?string
    {
        $country = CountriesEnum::tryFrom((string) str($isoCode)->upper());

        if ($country === null) {
            return null;
        }

        return method_exists($this, 'getPhone') && $this->getPhone()
            ? $country->getLabel().' '.$country->getDialCode()
            : $country->getLabel();
    }

    /** The first ISO code using a dialling code. Note that +1 and +7 are each shared by two countries. */
    public function getIsoCodeByDialCode($dialCode)
    {
        $isoCode = collect($this->getCountriesData())->where('dial_code', $dialCode)->first();

        return (string) str($isoCode['iso_code'] ?? '')->lower();
    }
}
