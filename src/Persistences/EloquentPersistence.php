<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Persistences;

use Illuminate\Database\Eloquent\Model;

class EloquentPersistence extends Model implements PersistenceInterface
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'persistences';

    /**
     * The users model name.
     *
     * @var string
     */
    protected static $usersModel = 'myGedung\Auth\Users\EloquentUser';

    /**
     * {@inheritDoc}
     */
    public function user()
    {
        return $this->belongsTo(static::$usersModel);
    }

    /**
     * Get the users model.
     *
     * @return string
     */
    public static function getUsersModel()
    {
        return static::$usersModel;
    }

    /**
     * Set the users model.
     *
     * @param  string  $usersModel
     * @return void
     */
    public static function setUsersModel($usersModel)
    {
        static::$usersModel = $usersModel;
    }
}
