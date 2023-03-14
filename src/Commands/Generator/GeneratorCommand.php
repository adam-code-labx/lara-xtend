<?php

namespace CodeLabX\XtendLaravel\Commands\Generator;

use Illuminate\Support\Str;

abstract class GeneratorCommand extends \Illuminate\Console\GeneratorCommand
{
    /**
     * {@inheritDoc}
     */
    public function handle(): void
    {
        parent::handle();
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $path = $this->laravel->basePath(config('xtend-laravel.directory'));

        return $path.'/Extensions/'.$name.'/';
    }

    /**
     * Get the root namespace for the class.
     */
    protected function rootNamespace(): string
    {
        return 'Xtend\\Extensions\\'.$this->getPackageName().'\\';
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     */
    protected function replaceClass($stub, $name): string
    {
        return parent::replaceClass($stub, class_basename($name));
    }

    /**
     * Get the package name.
     */
    protected function getPackageName(): string
    {
        if ($this->hasArgument('package-name')) {
            return $this->argument('package-name');
        }

        return $this->argument('name');
    }
}
