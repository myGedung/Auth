<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Hashing;

trait Hasher
{
    /**
     * The salt length.
     *
     * @var int
     */
    protected $saltLength = 22;

    /**
     * Create a random string for a salt.
     *
     * @return string
     */
    protected function createSalt()
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ./';
        $max = strlen($pool) - 1;
        $salt = '';
        for ($i = 0; $i < $this->saltLength; ++$i) {
            $salt .= $pool[random_int(0, $max)];
        }
        return $salt;
    }

    /**
     * Compares two strings $a and $b in length-constant time.
     *
     * @param  string  $a
     * @param  string  $b
     * @return boolean
     */
    protected function slowEquals($a, $b)
    {
        $diff = strlen($a) ^ strlen($b);

        for ($i = 0; $i < strlen($a) && $i < strlen($b); $i++) {
            $diff |= ord($a[$i]) ^ ord($b[$i]);
        }

        return $diff === 0;
    }
}
