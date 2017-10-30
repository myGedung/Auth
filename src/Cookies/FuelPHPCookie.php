<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Cookies;

use Fuel\Core\Cookie;

class FuelPHPCookie implements CookieInterface
{
    /**
     * The cookie key.
     *
     * @var string
     */
    protected $key = 'mygedung_auth';

    /**
     * Create a new FuelPHP cookie driver.
     *
     * @param  string  $key
     * @return void
     */
    public function __construct($key = null)
    {
        if (isset($key)) {
            $this->key = $key;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function put($value)
    {
        Cookie::set($this->key, json_encode($value), 2628000);
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        $value = Cookie::get($this->key);

        if ($value) {
            return json_decode($value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function forget()
    {
        Cookie::delete($this->key);
    }
}
