<?php

namespace jeremykenedy\LaravelBlocker\Test;

use jeremykenedy\LaravelBlocker\LaravelBlockerFacade;
use jeremykenedy\LaravelBlocker\LaravelBlockerServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return jeremykenedy\LaravelBlocker\LaravelBlockerServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [LaravelBlockerServiceProvider::class];
    }

    /**
     * Load package alias.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'laravelblocker',
        ];
    }
}
