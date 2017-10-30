<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Sessions;

use CI_Session as Session;

class CISession implements SessionInterface
{
    /**
     * The CodeIgniter session driver.
     *
     * @var \CI_Session
     */
    protected $store;

    /**
     * The session key.
     *
     * @var string
     */
    protected $key = 'mygedung_auth';

    /**
     * Create a new CodeIgniter Session driver.
     *
     * @param  \CI_Session  $store
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
        $this->store->set_userdata($this->key, serialize($value));
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        $value = $this->store->userdata($this->key);

        if ($value) {
            return unserialize($value);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function forget()
    {
        $this->store->unset_userdata($this->key);
    }
}
