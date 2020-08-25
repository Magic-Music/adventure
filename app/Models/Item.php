<?php

namespace App\Models;
use App\Models\Location;
use App\Models\Character;
use App\Models\Verb;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;

    public function location()
    {
        return $this->hasOne(Location::class);
    }

    public function character()
    {
        return $this->hasOne(Character::class);
    }

    public function verbs()
    {
        return $this->hasMany(Verb::class);
    }
}
