<?php

/**
 * Part of the Auth package.
*/

namespace myGedung\Auth\Checkpoints;

use myGedung\Auth\Users\UserInterface;
use RuntimeException;

class NotActivatedException extends RuntimeException
{
    /**
     * The user which caused the exception.
     *
     * @var \myGedung\Auth\Users\UserInterface
     */
    protected $user;

    /**
     * Returns the user.
     *
     * @return \myGedung\Auth\Users\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the user associated with Auth (does not log in).
     *
     * @param  \myGedung\Auth\Users\UserInterface
     * @return void
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }
}
