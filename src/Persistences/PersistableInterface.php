<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Persistences;

interface PersistableInterface
{
    /**
     * Returns the persistable key name.
     *
     * @return string
     */
    public function getPersistableKey();

    /**
     * Returns the persistable key value.
     *
     * @return string
     */
    public function getPersistableId();

    /**
     * Returns the persistable relationship name.
     *
     * @return string
     */
    public function getPersistableRelationship();

    /**
     * Generates a random persist code.
     *
     * @return string
     */
    public function generatePersistenceCode();
}
