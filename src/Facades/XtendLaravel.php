<?php

namespace CodeLabX\XtendLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodeLabX\XtendLaravel\XtendLaravel
 */
class XtendLaravel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CodeLabX\XtendLaravel\XtendLaravel::class;
    }
}
