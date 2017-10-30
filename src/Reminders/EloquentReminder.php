<?php

/**
 * Part of the Auth package.
 */

namespace myGedung\Auth\Reminders;

use Illuminate\Database\Eloquent\Model;

class EloquentReminder extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'reminders';

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'code',
        'completed',
        'completed_at',
    ];

    /**
     * Get mutator for the "completed" attribute.
     *
     * @param  mixed  $completed
     * @return bool
     */
    public function getCompletedAttribute($completed)
    {
        return (bool) $completed;
    }

    /**
     * Set mutator for the "completed" attribute.
     *
     * @param  mixed  $completed
     * @return void
     */
    public function setCompletedAttribute($completed)
    {
        $this->attributes['completed'] = (int) (bool) $completed;
    }
}
