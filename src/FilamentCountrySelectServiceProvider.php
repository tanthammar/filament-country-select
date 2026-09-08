<?php

namespace TantHammar\FilamentCountrySelect;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCountrySelectServiceProvider extends PackageServiceProvider
{
    public static $name = 'filament-country-select';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        // The flags are served as files rather than inlined, because inlining every flag into
        // every option puts the whole 3.4 MB flag library into the page, once per select.
        $this->publishes([
            __DIR__.'/../resources/svg' => public_path('vendor/'.static::$name.'/flags'),
        ], static::$name.'-flags');

        FilamentAsset::register([
            Css::make('country-select', __DIR__.'/../dist/country-select.css'),
        ], static::$name);
    }
}
