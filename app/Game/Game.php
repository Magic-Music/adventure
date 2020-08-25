<?php

namespace App\Game;

use App\Models\Location;

class Game
{
    public static $variables;
    
    private static function getInitialPosition()
    {
        //If not configured in the env file, start at location with id 1
        $startLocationSlug = config('adventure.start_location_slug') ?? (Location::find(1)->slug);

        return [
            'currentLocation' => $startLocationSlug,
            'gameover' => false,
            'itemLocations' => [],
            'itemsCarried' => [],
            'characterLocations' => [],
        ];
    }

    public static function clear()
    {
        session()->flush();
        self::$variables = null;
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
