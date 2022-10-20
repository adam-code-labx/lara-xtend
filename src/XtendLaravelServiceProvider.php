<?php

namespace CodeLabX\XtendLaravel;

use CodeLabX\XtendLaravel\Base\ExtendsProvider;
use CodeLabX\XtendLaravel\Base\XtendPackage;
use CodeLabX\XtendLaravel\Base\XtendPackageManager;
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
        $this->registerPackageProviders();
        $this->registerWithPackageFacades();
    }

    protected function registerPackageProviders(): void
    {
        $this->app->singleton('xtend.package-manager', function () {
            return new XtendPackageManager(resolve(XtendPackage::class));
        });

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
        $package
            ->name('xtend-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_xtend_packages-table')
            ->runsMigrations()
            ->hasCommands([
                XtendLaravelSetupCommand::class,
            ]);
    }

    public function bootingPackage()
    {
        $this->bootWithPackageFacades();
    }

    protected function bootWithPackageFacades(): void
    {
        $this->extendedPackageFacades->each(function (ExtendsProvider|string $extendedProvider) {
            $extendedProvider::withBoot();
        });
    }

    public function provides(): array
    {
        return [
            'xtend.package-manager',
        ];
    }
}
