<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Roles;

interface RoleableInterface
{
    /**
     * Returns all the associated roles.
     *
     * @return \IteratorAggregate
     */
    public function getRoles();

    /**
     * Checks if the user is in the given role.
     *
     * @param  mixed  $role
     * @return bool
     */
    public function inRole($role);
}
