<?php

namespace CodeLabX\XtendLaravel\Concerns;

use ReflectionClass;

trait HasPackageTools
{
    protected function getPackageBaseDir(): string
    {
        $reflector = new ReflectionClass(get_parent_class($this));

        return dirname($reflector->getFileName());
    }
}
