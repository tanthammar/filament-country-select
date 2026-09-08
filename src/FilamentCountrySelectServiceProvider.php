<?php

namespace TantHammar\FilamentCountrySelect;

use BladeUI\Icons\Factory;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
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
            ->hasViews()
            ->hasTranslations();

        $this->callAfterResolving(Factory::class, function (Factory $factory): void {
            $factory->add('flags', [
                'path' => __DIR__.'/../resources/svg',
                'prefix' => 'flags',
            ]);
        });
    }

    public function packageBooted(): void
    {
        $filesystem = new Filesystem;
        $files = $filesystem->allFiles(__DIR__.'/../resources/svg');

        collect($files)->each(function ($file) {
            $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);

            FilamentIcon::register([
                'flags::'.$filename => 'flags-'.$filename,
            ]);
        })->reject(function ($file) {
            return $file->getExtension() !== 'svg';
        });

        FilamentAsset::register([
            Css::make('country-select', __DIR__.'/../dist/country-select.css'),
        ], static::$name);
    }
}
