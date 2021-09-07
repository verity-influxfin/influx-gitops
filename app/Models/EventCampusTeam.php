<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCampusTeam extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_campus_teams';

    protected $fillable = [
        'school', 'name', 'intro'
    ];
}
