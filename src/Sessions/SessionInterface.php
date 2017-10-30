<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Sessions;

interface SessionInterface
{
    /**
     * Put a value in the Auth session.
     *
     * @param  mixed  $value
     * @return void
     */
    public function put($value);

    /**
     * Returns the Auth session value.
     *
     * @return mixed
     */
    public function get();

    /**
     * Removes the Auth session.
     *
     * @return void
     */
    public function forget();
}
