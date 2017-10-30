<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Reminders;

use myGedung\Auth\Users\UserInterface;

interface ReminderRepositoryInterface
{
    /**
     * Create a new reminder record and code.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @return string
     */
    public function create(UserInterface $user);

    /**
     * Check if a valid reminder exists.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @param  string  $code
     * @return bool
     */
    public function exists(UserInterface $user, $code = null);

    /**
     * Complete reminder for the given user.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @param  string  $code
     * @param  string  $password
     * @return bool
     */
    public function complete(UserInterface $user, $code, $password);

    /**
     * Remove expired reminder codes.
     *
     * @return int
     */
    public function removeExpired();
}
