<?php

namespace CodeLabX\XtendLaravel;

use CodeLabX\XtendLaravel\Base\ExtendsProvider;
use CodeLabX\XtendLaravel\Base\XtendPackage;
use CodeLabX\XtendLaravel\Base\XtendPackageManager;
use CodeLabX\XtendLaravel\Commands\Generator\PackageFacadeGenerator;
use CodeLabX\XtendLaravel\Commands\Generator\PackageGenerator;
use CodeLabX\XtendLaravel\Commands\Generator\PackageProviderGenerator;
use CodeLabX\XtendLaravel\Commands\XtendLaravelSetupCommand;
use CodeLabX\XtendLaravel\Commands\XtendPackageCommand;
use CodeLabX\XtendLaravel\Facades\XtendLaravel;
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
        $this->app->singleton('xtend.package-manager', function () {
            return new XtendPackageManager(resolve(XtendPackage::class));
        });

        if (! $this->app->runningUnitTests() && XtendLaravel::manager()->enabled()) {
            $this->registerPackageProviders();
            $this->registerWithPackageFacades();
        }
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
            ->filter(fn (SplFileInfo $file) => Str::endsWith($file->getRelativePath(), 'Providers') && ! Str::contains($file->getRelativePath(), 'vendor'))
            ->each(function (SplFileInfo $file) {
                $packageNamespace = Str::of($file->getRelativePath())->between('Extensions/', '/Providers')->remove('/src')->toString();
                $this->extendedPackageFacades->put($packageNamespace, 'Xtend\\Extensions\\'.$packageNamespace.'\\Facades\\Xtend'.$packageNamespace);
            })
            ->map(function (SplFileInfo $file): string {
                return (string) Str::of('Xtend')
                    ->append('\\', $file->getRelativePathname())
                    ->replace(['/', '.php', '\\src'], ['\\', '', '']);
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
            ->hasViews()
            ->hasMigration('create_xtend_packages-table')
            ->runsMigrations()
            ->hasCommands([
                XtendLaravelSetupCommand::class,
                XtendPackageCommand::class,
                PackageGenerator::class,
                PackageProviderGenerator::class,
                PackageFacadeGenerator::class,
            ]);

        // @todo Do we really need this here?
        $this->mergeConfigFrom($this->package->basePath("/../config/{$this->package->name}.php"), 'xtend/laravel');
    }

    public function bootingPackage()
    {
        $this->publishesConfig();
        if (! $this->app->runningUnitTests() && XtendLaravel::manager()->enabled()) {
            $this->bootWithPackageFacades();
        }
    }

    protected function bootWithPackageFacades(): void
    {
        $this->extendedPackageFacades->each(function (ExtendsProvider|string $extendedProvider) {
            $extendedProvider::withBoot();
        });
    }

    protected function publishesConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->package->basePath("/../config/{$this->package->name}.php") => config_path('xtend/laravel.php'),
            ], "{$this->package->shortName()}-config");
        }
    }

    public function provides(): array
    {
        return [
            'xtend.package-manager',
        ];
    }
}
