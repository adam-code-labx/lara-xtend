<?php

namespace CodeLabX\XtendLaravel;

use CodeLabX\XtendLaravel\Base\XtendPackageManager;

class XtendLaravel
{
    public function manager(): XtendPackageManager
    {
        return app('xtend.package-manager');
    }
}
