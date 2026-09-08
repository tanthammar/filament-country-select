<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use Illuminate\Support\Str;

trait HasCountryOptions
{
    public function getOptions(): array
    {
        $this->options = $this->getCountries();

        return $this->options;
    }

    /**
     * Keyed by ISO code rather than dialling code, because a dialling code is not unique:
     * the United States and Canada share +1, Russia and Kazakhstan share +7.
     */
    public function getCountries(): array
    {
        $data = [];

        foreach ($this->getCountriesData() as $country) {
            $data[$country['iso_code']] = $this->getOption($country);
        }

        return $data;
    }

    /** @param  array{label: string, dial_code: string, iso_code: string}  $country */
    public function getOption(array $country): string
    {
        return method_exists($this, 'allowHtml')
            ? $this->getHtmlOption($country)
            : $this->getPlainOption($country);
    }

    /** @param  array{label: string, dial_code: string, iso_code: string}  $country */
    public function getPlainOption(array $country): string
    {
        return $this->getPhone()
            ? $country['label'].' '.$country['dial_code']
            : $country['label'];
    }

    /** @param  array{label: string, dial_code: string, iso_code: string}  $country */
    public function getHtmlOption(array $country): string
    {
        return view('filament-country-select::select-option')
            ->with('label', $country['label'])
            ->with('dial_code', $this->getPhone() ? $country['dial_code'] : null)
            ->with('iso_code', Str::lower($country['iso_code']))
            ->with('hasFlags', $this->getFlags())
            ->render();
    }
}
