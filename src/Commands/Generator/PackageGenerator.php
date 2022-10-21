<?php

namespace CodeLabX\XtendLaravel\Commands\Generator;

class PackageGenerator extends GeneratorCommand
{
    protected $signature = 'xtend:generate-package
        {name : The composer vendor package name}
        {--force : Overwrite the package if it exists}';

    protected $description = 'Xtend package generator';

    protected $type = 'Package';

    /**
     * {@inheritDoc}
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/package-extend.stub';
    }

    /**
     * {@inheritDoc}
     */
    protected function getPath($name): string
    {
        return parent::getPath($name).class_basename($name).'Extend.php';
    }
}
