<?php

namespace TantHammar\FilamentCountrySelect\Concerns;

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
        return $this->buildOptions($this->getCountriesData());
    }

    /**
     * Whether markup and dialling codes are wanted is settled once for the whole list. Both can
     * be closures, and evaluating a closure per country would run Filament's parameter
     * injection once for every country in the list. They are read again on the next build, so
     * a closure that depends on other form state still keeps up.
     *
     * @param  iterable<array{key: string, iso_code: ?string, label: string, dial_code: ?string}>  $countries
     * @return array<string, string>
     */
    public function buildOptions(iterable $countries): array
    {
        $withDialCode = $this->wantsDialCode();
        $asHtml = $this->rendersHtmlOptions();

        $options = [];

        foreach ($countries as $country) {
            $options[$country['key']] = $asHtml
                ? $this->htmlOption($country, $withDialCode)
                : $this->plainOption($country, $withDialCode);
        }

        return $options;
    }

    /**
     * Markup is only needed to draw a flag. A filter can never draw one, because SelectFilter
     * builds its own inner Select and never allows html on it.
     */
    protected function rendersHtmlOptions(): bool
    {
        return false;
    }

    /**
     * Built as a string rather than rendered from a blade view. A view costs a few tenths of a
     * millisecond, which is nothing until it is paid once per country, per select, per request.
     *
     * @param  array{key: string, iso_code: ?string, label: string, dial_code: ?string}  $country
     */
    protected function htmlOption(array $country, bool $withDialCode): string
    {
        $html = '<div class="flex gap-x-2">';

        if (($flag = $this->flagUrl($country['iso_code'])) !== null) {
            $html .= '<img src="'.e($flag).'" alt="" width="24" height="20" class="h-5 w-6 shrink-0 object-contain" loading="lazy">';
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
