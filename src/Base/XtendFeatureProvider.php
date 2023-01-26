<?php

namespace CodeLabX\XtendLaravel\Base;

use Illuminate\Support\ServiceProvider;

/**
 * Class XtendFeatureProvider
 */
abstract class XtendFeatureProvider extends ServiceProvider
{
    /**
     * Create a new service provider instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(app());
    }

    abstract public function withRegister(): void;

    abstract public function withBoot(): void;
}
