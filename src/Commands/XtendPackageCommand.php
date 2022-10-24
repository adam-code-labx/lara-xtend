<?php

namespace CodeLabX\XtendLaravel\Commands;

use CodeLabX\XtendLaravel\Base\PackageInfo;
use CodeLabX\XtendLaravel\Facades\XtendLaravel;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class XtendPackageCommand extends Command
{
    protected $signature = 'xtend:package
        {name : The composer vendor package name}
        {--mono-repo : Use the mono-repo structure}
        {--force : Overwrite the package if it exists}';

    protected $description = 'Xtend package generator';

    protected PackageManifest $packageManifest;

    public function __construct(
        protected Filesystem $filesystem,
        protected Composer $composer)
    {
        parent::__construct();
    }

    public function handle(PackageManifest $packageManifest): int
    {
        //$this->packageManifest = $packageManifest;
        //$this->extendPackage();
        $this->addPackageToDontDiscover();

        return self::SUCCESS;
    }

    protected function extendPackage(): void
    {
        $this->call('xtend:generate-package', [
            'name' => $this->getPackageName(),
            '--force' => $this->option('force'),
        ]);

        $this->extendProviders();
        $this->createFacade();

        $this->option('mono-repo')
            ? $this->createMonoRepoPackageStructure()
            : $this->createResourcesStructure($this->getXtendPackagePath());

        $this->components->info('Installing package...');
        XtendLaravel::manager()->installPackage(
            PackageInfo::make($this->argument('name'))->enabled(),
        );
    }

    protected function extendProviders(): void
    {
        $providers = $this->packageManifest->manifest[$this->argument('name')]['providers'] ?? [];

        foreach ($providers as $provider) {
            $this->call('xtend:generate-provider', [
                'name' => $provider,
                'package-name' => $this->getPackageName(),
                'namespace' => Str::of($provider)->beforeLast('\\')->__toString(),
                '--force' => $this->option('force'),
            ]);
        }
    }

    protected function createFacade(): void
    {
        $this->call('xtend:generate-facade', [
            'name' => 'Xtend'.$this->getPackageName(),
            'package-name' => $this->getPackageName(),
            '--force' => $this->option('force'),
        ]);
    }

    protected function createMonoRepoPackageStructure(): void
    {
        if ($this->filesystem->isDirectory($path = $this->laravel->basePath('vendor/'.$this->argument('name').'/packages'))) {
            collect($this->filesystem->directories($path))->each(function ($packagePath) {
                $packageName = Str::of(basename($packagePath))->studly()->__toString();

                $this->filesystem->ensureDirectoryExists($this->getXtendPackagePath().'/Packages/'.$packageName);
                $this->createResourcesStructure($this->getXtendPackagePath().'/Packages/'.$packageName);
            });

        }
    }

    protected function createResourcesStructure($packageDir): void
    {
        foreach (['assets', 'lang', 'views'] as $resourceDirectory) {
            $this->filesystem->ensureDirectoryExists($packageDir.'/Resources/'.$resourceDirectory);
            $this->filesystem->put($packageDir.'/Resources/'.$resourceDirectory.'/.gitkeep', '');
        }
    }

    protected function getPackageName(): string
    {
        return Str::of($this->argument('name'))->afterLast('/')->studly()->__toString();
    }

    protected function getXtendPackagePath(): string
    {
        $path = $this->laravel->basePath(config('xtend-laravel.directory'));
        return $path.'/Extensions/'.$this->getPackageName();
    }

    protected function addPackageToDontDiscover(): void
    {
        $composerPath = app()->basePath('composer.json');
        $composer = json_decode(file_get_contents($composerPath), true);

        $this->components->info('Adding package to dont-discover...');

        $composer['extra']['laravel']['dont-discover'][] = $this->argument('name');
        file_put_contents($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->composer->dumpAutoloads();
    }
}
