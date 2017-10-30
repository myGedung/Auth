<?php

/**
 * Part of the Auth package.
 *
 */

namespace myGedung\Auth\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Auth extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'auth';
    }
}
