<?php

namespace CodeLabX\XtendLaravel;

use CodeLabX\XtendLaravel\Base\ExtendsProvider;
use CodeLabX\XtendLaravel\Commands\XtendLaravelSetupCommand;
use CodeLabX\XtendLaravel\Services\Translation\FileLoader;
use CodeLabX\XtendLaravel\Services\Translation\TranslationServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\Finder\SplFileInfo;

class XtendLaravelServiceProvider extends PackageServiceProvider
{
    protected Collection $extendedPackageFacades;

    public function registeringPackage()
    {
        $this->setViewsPath();
        $this->app->register(TranslationServiceProvider::class);

        $this->app->singleton('translation.loader', function ($app) {
            return new FileLoader($app['files'], $app['path.lang']);
        });

        $this->registerPackageProviders();
        $this->registerWithPackageFacades();
    }

    protected function registerPackageProviders(): void
    {
        $this->getPackageProviders()->each(function ($provider) {
            $this->app->register($provider);
        });
    }

    protected function getPackageProviders(): Collection
    {
        $this->extendedPackageFacades = collect();

        $xtendPackageDir = 'xtend';

        return collect(app(Filesystem::class)->allFiles(app()->basePath($xtendPackageDir)))
            ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getRelativePath(), 'Providers'))
            ->each(function (SplFileInfo $file) {
                $packageNamespace = Str::of($file->getRelativePath())->between('Extensions/', '/Providers')->toString();
                $this->extendedPackageFacades->put($packageNamespace, 'Xtend\\Extensions\\'.$packageNamespace.'\\Facades\\'.$packageNamespace.'Extend');
            })
            ->map(function (SplFileInfo $file): string {
                return (string) Str::of('Xtend')
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php'], ['\\', '']);
            });
    }

    protected function registerWithPackageFacades(): void
    {
        $this->extendedPackageFacades->each(function (ExtendsProvider|string $extendedProvider) {
            $extendedProvider::withRegister();
        });
    }

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

    public function bootingPackage()
    {
        $this->bootWithPackageFacades();
        $this->loadViewsFrom($this->package->basePath('/../resources/views'), 'xtend-laravel');
        $this->loadTranslationsFrom($this->package->basePath('/../resources/lang'), 'xtend-laravel');
    }

    protected function bootWithPackageFacades(): void
    {
        $this->extendedPackageFacades->each(function (ExtendsProvider|string $extendedProvider) {
            $extendedProvider::withBoot();
        });
    }

    protected function setViewsPath(): void
    {
        config()->set('view.paths', array_merge([__DIR__.'/../resources/views'], config('view.paths')));
    }
}
