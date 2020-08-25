<?php

namespace App\Game;

use App\Models\Location;

class Game
{
    private static $variables;
    
    private static function getInitialPosition()
    {
        return [
            'currentLocation' => 1,
            'gameover' => false,
            'itemLocations' => [],
            'itemsCarried' => [],
            'characterLocations' => [],
        ];
    }
    
    public static function initialise()
    {
        self::$variables = session('adventure') ?? self::getInitialPosition();
    }
    
    public static function save()
    {
        session(['adventure' => self::$variables]);
    }

    public static function get($key)
    {
        return self::$variables[$key] ?? null;
    }

    public static function set($key, $value)
    {
        self::$variables[$key] = $value;
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
}
