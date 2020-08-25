<?php

namespace App\Game;

use App\Game\Game;
use App\Models\Location;

class Player
{
    private $directions = [
        'n' => 'north',
        'ne' => 'northeast',
        'e' => 'east',
        'se' => 'southeast',
        's' => 'south',
        'sw' => 'southwest',
        'w' => 'west',
        'nw' => 'northwest',
        'u' => 'up',
        'd' => 'down'
    ];
    
    public function go($params)
    {
        $direction = $this->directions[$params[0]] ?? $params[0];
        return $this->move([$direction]);
    }
    
    public function move($params)
    {
        $direction = $params[0];
        $location = Location::where('slug', Game::currentLocation())->first();
        $newLocation = $location->$direction;
        
        if ($newLocation == '' || is_null($newLocation)) {
            return "You cannot go $direction";
        }
        
        Game::currentLocation($newLocation);
        return ($this->look());
    }
    
    public function look()
    {
        $location =  "You are " . Location::where('slug', Game::currentLocation())->first()->long_description;
//        $items = "You can see " . implode(Items::list());
        return $location;
    }    
}