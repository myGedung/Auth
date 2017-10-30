<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Users;

interface UserInterface
{
    /**
     * Returns the user primary key.
     *
     * @return int
     */
    public function getUserId();

    /**
     * Returns the user login.
     *
     * @return string
     */
    public function getUserLogin();

    /**
     * Returns the user login attribute name.
     *
     * @return string
     */
    public function getUserLoginName();

    /**
     * Returns the user password.
     *
     * @return string
     */
    public function getUserPassword();
}
