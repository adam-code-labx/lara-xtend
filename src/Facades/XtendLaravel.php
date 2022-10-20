<?php

namespace CodeLabX\XtendLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class XtendLaravel
 *
 * @method static \CodeLabX\XtendLaravel\Base\XtendPackageManager manager
 *
 * @see \CodeLabX\XtendLaravel\XtendLaravel
 */
class XtendLaravel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \CodeLabX\XtendLaravel\XtendLaravel::class;
    }
}
