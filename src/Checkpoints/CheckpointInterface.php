<?php

/**
 * Part of the Auth package.
*/

namespace myGedung\Auth\Checkpoints;

use myGedung\Auth\Users\UserInterface;

interface CheckpointInterface
{
    /**
     * Checkpoint after a user is logged in. Return false to deny persistence.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @return bool
     */
    public function login(UserInterface $user);

    /**
     * Checkpoint for when a user is currently stored in the session.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @return bool
     */
    public function check(UserInterface $user);

    /**
     * Checkpoint for when a failed login attempt is logged. User is not always
     * passed and the result of the method will not affect anything, as the
     * login failed.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @return void
     */
    public function fail(UserInterface $user = null);
}
