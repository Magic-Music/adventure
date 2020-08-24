<?php

namespace App\Game;

use App\Models\Location;

class Game
{
    private static $currentPosition;
    
    private static function getInitialPosition()
    {
        return [
            'currentLocation' => 1,
            'gameover' => false,
            'itemLocations' => [],
            'characterLocations' => [],
        ];
    }
    
    public static function initialise()
    {
        self::$currentPosition = session('adventure') ?? self::getInitialPosition();
    }
    
    public static function save()
    {
        session(['adventure' => self::$currentPosition]);      
    }
    
    public static function currentLocation($update = false)
    {
        if($update !== false) {
            self::set('currentLocation', $update);
        } else {
            return self::get('currentLocation');
        }
    }
    
    public static function gameover($update = 'no')
    {
        if($update != 'no') {
            self::set('gameover', $update);
        } else {
            return self::get('gameover');
        }
    }
    
    public static function get($key)
    {
        return self::$currentPosition[$key] ?? null;
    }
    
    public static function set($key, $value)
    {
        self::$currentPosition[$key] = $value;
    }

}
