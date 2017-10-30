<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Throttling;

use myGedung\Auth\Users\UserInterface;

interface ThrottleRepositoryInterface
{
    /**
     * Returns the global throttling delay, in seconds.
     *
     * @return int
     */
    public function globalDelay();

    /**
     * Returns the IP address throttling delay, in seconds.
     *
     * @param  string  $ipAddress
     * @return int
     */
    public function ipDelay($ipAddress);

    /**
     * Returns the throttling delay for the given user, in seconds.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @return int
     */
    public function userDelay(UserInterface $user);

    /**
     * Logs a new throttling entry.
     *
     * @param  string  $ipAddress
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @return void
     */
    public function log($ipAddress = null, UserInterface $user = null);
}
