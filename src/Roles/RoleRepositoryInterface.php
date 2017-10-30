<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Roles;

interface RoleRepositoryInterface
{
    /**
     * Finds a role by the given primary key.
     *
     * @param  int  $id
     * @return \myGedung\Auth\Roles\RoleInterface
     */
    public function findById($id);

    /**
     * Finds a role by the given slug.
     *
     * @param  string  $slug
     * @return \myGedung\Auth\Roles\RoleInterface
     */
    public function findBySlug($slug);

    /**
     * Finds a role by the given name.
     *
     * @param  string  $name
     * @return \myGedung\Auth\Roles\RoleInterface
     */
    public function findByName($name);
}
