<?php

namespace CodeLabX\XtendLaravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Str;

class XtendPackageCommand extends Command
{
    protected $signature = 'xtend:package
        {name : The composer vendor package name}
        {--force : Overwrite the package if it exists}';

    protected $description = 'Xtend package generator';

    protected PackageManifest $packageManifest;

    protected array $monoRepoPackages = [];

    public function handle(PackageManifest $packageManifest): int
    {
        $this->packageManifest = $packageManifest;
        $this->extendPackage();

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

    protected function getPackageName(): string
    {
        return Str::of($this->argument('name'))->afterLast('/')->studly()->__toString();
    }
}
