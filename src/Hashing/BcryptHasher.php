<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Hashing;

class BcryptHasher implements HasherInterface
{
    use Hasher;

    /**
     * The hash strength.
     *
     * @var int
     */
    public $strength = 8;

    /**
     * {@inheritDoc}
     */
    public function hash($value)
    {
        $salt = $this->createSalt();

        // Format the strength
        $strength = str_pad($this->strength, 2, '0', STR_PAD_LEFT);

        // Create prefix - "$2y$"" fixes blowfish weakness
        $prefix = PHP_VERSION_ID < 50307 ? '$2a$' : '$2y$';

        return crypt($value, $prefix.$strength.'$'.$salt.'$');
    }

    /**
     * {@inheritDoc}
     */
    public function check($value, $hashedValue)
    {
        return $this->slowEquals(crypt($value, $hashedValue), $hashedValue);
    }
}
