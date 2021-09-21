<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\EventCampusTeam;

class MappingData
{
	public function getEventTeams(){
        $data = [];
        $plucked = EventCampusTeam::all()->pluck(
          'name',
          'id'
        );
        if(!empty($plucked)){
            $data =  $plucked->all();
        }
        return $data;
    }
}
