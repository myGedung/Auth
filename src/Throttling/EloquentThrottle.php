<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Throttling;

use Illuminate\Database\Eloquent\Model;

class EloquentThrottle extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'throttle';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'ip',
        'type',
    ];
}
