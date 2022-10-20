<?php

namespace CodeLabX\XtendLaravel\Base;

class XtendPackageManager
{
    public function __construct(protected XtendPackage $xtend)
    {
    }

    public function installPackages(): void
    {
        foreach ($this->getPackages() as $package) {
            $this->xtend->updateOrCreate(
                ['name' => $package->getName()],
                $package->toArray()
            );
        }
    }

    protected function getPackages(): array
    {
        return [
            PackageInfo::make('laravel/framework')->enabled(),
            PackageInfo::make('livewire/livewire')->enabled(),
        ];
    }
}
