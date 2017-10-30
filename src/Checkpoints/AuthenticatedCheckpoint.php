<?php

/**
 * Part of the Auth package.
*/

namespace myGedung\Auth\Checkpoints;

use myGedung\Auth\Users\UserInterface;

trait AuthenticatedCheckpoint
{
    /**
     * {@inheritDoc}
     */
    public function fail(UserInterface $user = null)
    {
    }
}
