<?php

namespace CodeLabX\XtendLaravel\Base;

use Composer\InstalledVersions;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class XtendPackageProvider
 */
abstract class XtendPackageProvider extends ServiceProvider
{
    protected static string $packageToXtend = 'xtend-laravel/xtend';

    public function __construct(Application $app)
    {
        $this->throwErrorIfDependencyIsNotInstalled();
        parent::__construct($app);
    }

    /**
     * @throws \Exception
     */
    protected function throwErrorIfDependencyIsNotInstalled(): void
    {
        try {
            InstalledVersions::getVersion(static::$packageToXtend);
        } catch (\Throwable $e) {
            throw new \Exception(static::$packageToXtend.' dependency is not installed. Please install it before this package can be extended.');
        }
    }
}
