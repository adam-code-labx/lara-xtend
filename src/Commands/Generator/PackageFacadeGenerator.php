<?php

namespace CodeLabX\XtendLaravel\Commands\Generator;

class PackageFacadeGenerator extends GeneratorCommand
{
    protected $signature = 'xtend:generate-facade
        {name : The package facade name}
        {package-name : The composer vendor package name}
        {--force : Overwrite the facade if it exists}';

    protected $description = 'Xtend package facade generator';

    protected $type = 'Facade';

    /**
     * {@inheritDoc}
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/package-facade.stub';
    }

    /**
     * {@inheritDoc}
     */
    protected function getPath($name): string
    {
        $facade = class_basename($name);
        $name = $this->argument('package-name');

        return parent::getPath($name).'Facades/'.$facade.'.php';
    }

    /**
     * {@inheritDoc}
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);
        $stub = str_replace('{{ packageName }}', $this->argument('package-name'), $stub);

        return $stub;
    }
}
