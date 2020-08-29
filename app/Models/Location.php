<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Item;
use App\Models\Character;

class Location extends Model
{
    protected $directions = [
        'north',
        'northeast',
        'east',
        'southeast',
        'south',
        'southwest',
        'west',
        'northwest',
        'up',
        'down',        
    ];
    
    protected $fillable = [
        'id',
        'long_description',
        'description',
        'north',
        'northeast',
        'east',
        'southeast',
        'south',
        'southwest',
        'west',
        'northwest',
        'up',
        'down',
    ];

    public $timestamps = false;

    public function getExitsAttribute()
    {
        $exits = [];
        foreach ($this->directions as $direction) {
            if($this->attributes[$direction]) {
                $exits[] = $direction;
            }
        }
        return $exits;
    }
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
