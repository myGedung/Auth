<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Cookies;

interface CookieInterface
{
    /**
     * Put a value in the Auth cookie (to be stored until it's cleared).
     *
     * @param  mixed  $value
     * @return void
     */
    public function put($value);

    /**
     * Returns the Auth cookie value.
     *
     * @return mixed
     */
    public function get();

    /**
     * Remove the Auth cookie.
     *
     * @return void
     */
    public function forget();
}
