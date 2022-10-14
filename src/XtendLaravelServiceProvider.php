<?php

namespace CodeLabX\XtendLaravel;

use CodeLabX\XtendLaravel\Commands\XtendLaravelSetupCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class XtendLaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('xtend-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_xtend-laravel_table')
            ->hasCommands([
                XtendLaravelSetupCommand::class,
            ]);
    }
}
