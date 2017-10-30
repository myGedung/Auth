<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Permissions;

interface PermissionsInterface
{
    /**
     * Returns if access is available for all given permissions.
     *
     * @param  array|string  $permissions
     * @return bool
     */
    public function hasAccess($permissions);

    /**
     * Returns if access is available for any given permissions.
     *
     * @param  array|string  $permissions
     * @return bool
     */
    public function hasAnyAccess($permissions);
}
