<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Native\Facades;

use myGedung\Auth\Native\AuthBootstrapper;

class Auth
{
    /**
     * The Auth instance.
     *
     * @var \myGedung\Auth\Auth
     */
    protected $auth;

    /**
     * The Native Bootstraper instance.
     *
     * @var \myGedung\Auth\Native\AuthBootstrapper
     */
    protected static $instance;

    /**
     * Constructor.
     *
     * @param  \myGedung\Auth\Native\AuthBootstrapper  $bootstrapper
     * @return void
     */
    public function __construct(AuthBootstrapper $bootstrapper = null)
    {
        if ($bootstrapper === null) {
            $bootstrapper = new AuthBootstrapper;
        }

        $this->auth = $bootstrapper->createAuth();
    }

    /**
     * Returns the Auth instance.
     *
     * @return \myGedung\Auth\Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Creates a new Native Bootstraper instance.
     *
     * @param  \myGedung\Auth\Native\AuthBootstrapper  $bootstrapper
     * @return void
     */
    public static function instance(AuthBootstrapper $bootstrapper = null)
    {
        if (static::$instance === null) {
            static::$instance = new static($bootstrapper);
        }

        return static::$instance;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array  $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::instance()->getAuth();

        switch (count($args)) {
            case 0:
                return $instance->{$method}();

            case 1:
                return $instance->{$method}($args[0]);

            case 2:
                return $instance->{$method}($args[0], $args[1]);

            case 3:
                return $instance->{$method}($args[0], $args[1], $args[2]);

            case 4:
                return $instance->{$method}($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}
