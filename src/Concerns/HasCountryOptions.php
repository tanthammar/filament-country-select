<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

use TantHammar\FilamentCountrySelect\Enums\CountriesEnum;

trait HasCountryOptions
{
    public function getOptions(): array
    {
        $this->options = $this->getCountries();

        return $this->options;
    }

    public function getCountries(): array
    {
        return $this->buildOptions($this->getCountriesData());
    }

    /**
     * @param  iterable<array{key: string, iso_code: ?string, label: string, dial_code: ?string}>  $countries
     * @return array<string, string>
     */
    public function buildOptions(iterable $countries): array
    {
        $withDialCode = $this->getPhone();
        $asHtml = $this->rendersHtmlOptions();

        $options = [];

        foreach ($countries as $country) {
            $options[$country['key']] = $asHtml
                ? $this->htmlOption($country, $withDialCode)
                : $this->plainOption($country, $withDialCode);
        }

        return $options;
    }

    protected function rendersHtmlOptions(): bool
    {
        return false;
    }

    /**
     * @param  array{key: string, iso_code: ?string, label: string, dial_code: ?string}  $country
     */
    protected function htmlOption(array $country, bool $withDialCode): string
    {
        $html = '<div class="flex gap-x-2">';

        if ($country['iso_code'] !== null) {
            $html .= CountriesEnum::from($country['iso_code'])->getFlag();
        }

        $html .= '<div>'.e($country['label']);

        if ($withDialCode && $country['dial_code'] !== null) {
            $html .= ' <span class="text-xs">'.e($country['dial_code']).'</span>';
        }

        return $html.'</div></div>';
    }

    /** @param  array{key: string, iso_code: ?string, label: string, dial_code: ?string}  $country */
    protected function plainOption(array $country, bool $withDialCode): string
    {
        return $withDialCode && $country['dial_code'] !== null
            ? $country['label'].' '.$country['dial_code']
            : $country['label'];
    }
}
