<?php

namespace CodeLabX\XtendLaravel\Commands\Generator;

class PackageProviderGenerator extends GeneratorCommand
{
    protected $signature = 'xtend:generate-provider
        {name : The name of the provider}
        {namespace : The namespace of the provider}
        {package-name : The composer vendor package name}
        {--spatie-tools : Use the Spatie package tools}
        {--force : Overwrite the provider if it exists}';

    protected $description = 'Xtend package provider generator';

    protected $type = 'Provider';

    /**
     * {@inheritDoc}
     */
    protected function getStub()
    {
        if ($this->option('spatie-tools')) {
            return __DIR__.'/stubs/package-tools-provider.stub';
        }

        return __DIR__.'/stubs/package-provider.stub';
    }

    /**
     * {@inheritDoc}
     */
    protected function getPath($name): string
    {
        $provider = class_basename($name);
        $name = $this->argument('package-name');

        return parent::getPath($name).'Providers/'.$provider.'.php';
    }

    /**
     * {@inheritDoc}
     */
    protected function getNamespace($name)
    {
        return $this->rootNamespace().'Providers';
    }

    /**
     * {@inheritDoc}
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replacePackageProvider($stub, $name);
    }

    /**
     * Replace the package provider for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replacePackageProvider($stub, $name): string
    {
        $replace = [
            'packageProvider' => $this->argument('namespace').'\\'.class_basename($name),
            'packageBaseProvider' => str_replace('ServiceProvider', 'BaseServiceProvider', class_basename($name)),
        ];

        foreach ($replace as $key => $value) {
            $stub = str_replace('{{ '.$key.' }}', $value, $stub);
        }

        return $stub;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name): string
    {
        $class = class_basename(str_replace($this->getNamespace($name).'\\', '', $name));

        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }
}
