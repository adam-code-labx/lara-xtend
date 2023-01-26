<?php

namespace CodeLabX\XtendLaravel\Base;

class XtendPackageManager
{
    public function __construct(protected XtendPackage $xtend) {}

    public function enabled(): bool
    {
        return false;
    }

    public function installPackage(PackageInfo $package): void
    {
        $this->xtend->create($package->toArray());
    }

    public function installDefaultPackages(): void
    {
        foreach ($this->getDefaultPackages() as $package) {
            $this->xtend->updateOrCreate(
                ['name' => $package->getName()],
                $package->toArray()
            );
        }
    }

    protected function getDefaultPackages(): array
    {
        return [
            PackageInfo::make('laravel/framework')->namespace()->enabled(),
            PackageInfo::make('livewire/livewire')->namespace()->enabled(),
        ];
    }
}
