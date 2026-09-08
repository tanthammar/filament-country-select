<?php

namespace TantHammar\FilamentCountrySelect;

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
        $this->publishes([
            __DIR__.'/../resources/flags' => public_path('vendor/'.static::$name.'/flags'),
        ], static::$name.'-flags');
    }
}
