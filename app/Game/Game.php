<?php

namespace App\Game;

use App\Models\Location;

class Game
{
    public static $currentLocation = 1;
    
    public static $gameover = false;
    
    public function look()
    {
        return "You are " . Location::find(self::$currentLocation)->long_description;
    }
}
