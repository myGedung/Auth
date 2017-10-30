<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Sessions;

use Fuel\Core\Session_Driver as Session;

class FuelPHPSession implements SessionInterface
{
    /**
     * The FuelPHP session driver.
     *
     * @var \Fuel\Core\Session_Driver
     */
    protected $store;

    /**
     * The session key.
     *
     * @var string
     */
    protected $key = 'mygedung_auth';

    /**
     * Create a new FuelPHP Session driver.
     *
     * @param  \Fuel\Core\Session_Driver  $store
     * @param  string  $key
     * @return void
     */
    public function __construct(Session $store, $key = null)
    {
        $this->store = $store;

        if (isset($key)) {
            $this->key = $key;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function put($value)
    {
        $this->store->set($this->key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->store->get($this->key);
    }

    /**
     * {@inheritDoc}
     */
    public function forget()
    {
        $this->store->delete($this->key);
    }
}
