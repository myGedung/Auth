<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Hashing;

interface HasherInterface
{
    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @return string
     * @throws \RuntimeException
     */
    public function hash($value);

    /**
     * Checks the string against the hashed value.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @return bool
     */
    public function check($value, $hashedValue);
}
