<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Persistences;

use myGedung\Auth\Cookies\CookieInterface;
use myGedung\Auth\Persistences\PersistableInterface;
use myGedung\Auth\Sessions\SessionInterface;
use myGedung\Support\Traits\RepositoryTrait;

class IlluminatePersistenceRepository implements PersistenceRepositoryInterface
{
    use RepositoryTrait;

    /**
     * Single session.
     *
     * @var boolean
     */
    protected $single = false;

    /**
     * Session storage driver.
     *
     * @var \myGedung\Auth\Sessions\SessionInterface
     */
    protected $session;

    /**
     * Cookie storage driver.
     *
     * @var \myGedung\Auth\Cookies\CookieInterface
     */
    protected $cookie;

    /**
     * Model name.
     *
     * @var string
     */
    protected $model = 'myGedung\Auth\Persistences\EloquentPersistence';

    /**
     * Create a new Auth persistence repository.
     *
     * @param  \myGedung\Auth\Sessions\SessionInterface  $session
     * @param  \myGedung\Auth\Cookies\CookieInterface  $cookie
     * @param  string  $model
     * @param  bool  $single
     * @return void
     */
    public function __construct(
        SessionInterface $session,
        CookieInterface $cookie,
        $model = null,
        $single = false
    ) {
        if (isset($model)) {
            $this->model = $model;
        }

        if (isset($session)) {
            $this->session = $session;
        }

        if (isset($cookie)) {
            $this->cookie  = $cookie;
        }

        $this->single = $single;
    }

    /**
     * {@inheritDoc}
     */
    public function check()
    {
        if ($code = $this->session->get()) {
            return $code;
        }

        if ($code = $this->cookie->get()) {
            return $code;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function findByPersistenceCode($code)
    {
        $persistence = $this->createModel()
            ->newQuery()
            ->where('code', $code)
            ->first();

        return $persistence ? $persistence : false;
    }

    /**
     * {@inheritDoc}
     */
    public function findUserByPersistenceCode($code)
    {
        $persistence = $this->findByPersistenceCode($code);

        return $persistence ? $persistence->user : false;
    }

    /**
     * {@inheritDoc}
     */
    public function persist(PersistableInterface $persistable, $remember = false)
    {
        if ($this->single) {
            $this->flush($persistable);
        }

        $code = $persistable->generatePersistenceCode();

        $this->session->put($code);

        if ($remember === true) {
            $this->cookie->put($code);
        }

        $persistence = $this->createModel();

        $persistence->{$persistable->getPersistableKey()} = $persistable->getPersistableId();
        $persistence->code = $code;

        return $persistence->save();
    }

    /**
     * {@inheritDoc}
     */
    public function persistAndRemember(PersistableInterface $persistable)
    {
        return $this->persist($persistable, true);
    }

    /**
     * {@inheritDoc}
     */
    public function forget()
    {
        $code = $this->check();

        if ($code === null) {
            return;
        }

        $this->session->forget();
        $this->cookie->forget();

        return $this->remove($code);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($code)
    {
        return $this->createModel()
            ->newQuery()
            ->where('code', $code)
            ->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function flush(PersistableInterface $persistable, $forget = true)
    {
        if ($forget) {
            $this->forget();
        }

        foreach ($persistable->{$persistable->getPersistableRelationship()}()->get() as $persistence) {
            if ($persistence->code !== $this->check()) {
                $persistence->delete();
            }
        }
    }
}
