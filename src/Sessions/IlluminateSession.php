<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Sessions;

use Illuminate\Session\Store as SessionStore;

class IlluminateSession implements SessionInterface
{
    /**
     * The session store object.
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * The session key.
     *
     * @var string
     */
    protected $key = 'mygedung_auth';

    /**
     * Create a new Illuminate Session driver.
     *
     * @param  \Illuminate\Session\Store  $session
     * @param  string  $key
     * @return void
     */
    public function __construct(SessionStore $session, $key = null)
    {
        $this->session = $session;

        if (isset($key)) {
            $this->key = $key;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function put($value)
    {
        $this->session->put($this->key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function get()
    {
        return $this->session->get($this->key);
    }

    /**
     * {@inheritDoc}
     */
    public function forget()
    {
        $this->session->forget($this->key);
    }
}
