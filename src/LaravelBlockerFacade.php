<?php

namespace jeremykenedy\LaravelBlocker;

use Illuminate\Support\Facades\Facade;

class LaravelBlockerFacade extends Facade
{
    /**
     * Gets the facade accessor.
     *
     * @return string The facade accessor.
     */
    protected static function getFacadeAccessor()
    {
        return 'laravelblocker';
    }
}
