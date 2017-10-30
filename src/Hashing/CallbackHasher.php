<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Hashing;

use Closure;

class CallbackHasher implements HasherInterface
{
    /**
     * The closure used for hashing a value.
     *
     * @var \Closure
     */
    protected $hash;

    /**
     * The closure used for checking a hashed value.
     *
     * @var \Closure
     */
    protected $check;

    /**
     * Create a new callback hasher instance.
     *
     * @param  \Closure  $hash
     * @param  \Closure  $check
     * @return void
     */
    public function __construct(Closure $hash, Closure $check)
    {
        $this->hash = $hash;

        $this->check = $check;
    }

    /**
     * {@inheritDoc}
     */
    public function hash($value)
    {
        $callback = $this->hash;

        return $callback($value);
    }

    /**
     * {@inheritDoc}
     */
    public function check($value, $hashedValue)
    {
        $callback = $this->check;

        return $callback($value, $hashedValue);
    }
}
