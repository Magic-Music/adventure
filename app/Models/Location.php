<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\Character;

class Location extends Model
{
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
