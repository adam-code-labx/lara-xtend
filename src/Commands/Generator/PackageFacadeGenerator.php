<?php

namespace CodeLabX\XtendLaravel\Commands\Generator;

class PackageFacadeGenerator extends GeneratorCommand
{
    protected $name = 'xtend:generate-facade
        {name : The package facade name}
        {package-name : The name of the package}
        {--force : Overwrite the provider if it exists}';

    protected $description = 'Xtend package facade generator';

    /**
     * {@inheritDoc}
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/package-facade.stub';
    }
}
