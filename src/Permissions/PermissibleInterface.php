<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Permissions;

interface PermissibleInterface
{
    /**
     * Returns the permissions instance.
     *
     * @return \myGedung\Auth\Permissions\PermissionsInterface
     */
    public function getPermissionsInstance();

    /**
     * Adds a permission.
     *
     * @param  string  $permission
     * @param  bool  $value
     * @return \myGedung\Auth\Permissions\PermissibleInterface
     */
    public function addPermission($permission, $value = true);

    /**
     * Updates a permission.
     *
     * @param  string  $permission
     * @param  bool  $value
     * @param  bool  $create
     * @return \myGedung\Auth\Permissions\PermissibleInterface
     */
    public function updatePermission($permission, $value = true, $create = false);

    /**
     * Removes a permission.
     *
     * @param  string  $permission
     * @return \myGedung\Auth\Permissions\PermissibleInterface
     */
    public function removePermission($permission);
}
