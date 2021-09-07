<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCampusMember extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_campus_members';

    protected $fillable = [
        'name',
        'dept',
        'grade',
        'mobile',
        'email',
        'self_intro',
        'resume',
        'motivation',
        'portfolio',
        'fb_link',
        'ig_link',
        'bonus',
        'team_id'
    ];
}
