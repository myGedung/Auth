<?php

/**
 * Part of the Auth package.
*/

namespace myGedung\Auth\Activations;

use myGedung\Auth\Users\UserInterface;

interface ActivationRepositoryInterface
{
    /**
     * Create a new activation record and code.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @return \myGedung\Auth\Activations\ActivationInterface
     */
    public function create(UserInterface $user);

    /**
     * Checks if a valid activation for the given user exists.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @param  string  $code
     * @return \myGedung\Auth\Activations\ActivationInterface|bool
     */
    public function exists(UserInterface $user, $code = null);

    /**
     * Completes the activation for the given user.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @param  string  $code
     * @return bool
     */
    public function complete(UserInterface $user, $code);

    /**
     * Checks if a valid activation has been completed.
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @return \myGedung\Auth\Activations\ActivationInterface|bool
     */
    public function completed(UserInterface $user);

    /**
     * Remove an existing activation (deactivate).
     *
     * @param  \myGedung\Auth\Users\UserInterface  $user
     * @return bool|null
     */
    public function remove(UserInterface $user);

    /**
     * Remove expired activation codes.
     *
     * @return int
     */
    public function removeExpired();
}
