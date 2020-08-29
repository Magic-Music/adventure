<?php

namespace App\Game;

use App\Game\Items;
use App\Models\Location;

class Game
{
    public static $variables;

    public static function clear()
    {
        session()->flush();
        self::$variables = null;
    }
    
    private static function reset()
    {
        //If not configured in the env file, start at location with id 1
        $startLocationSlug = config('adventure.start_location_slug') ?? (Location::find(1)->slug);

        self::$variables = [
            'currentLocation' => $startLocationSlug,
            'gameover' => false,
            'itemLocations' => [],
            'itemsCarried' => [],
            'characterLocations' => [],
            'locationsVisited' => [],
        ];
        
        Items::initialise();
    }

    public static function initialise()
    {
        self::$variables = session('adventure') ?? null;
        if(!self::$variables) {
            self::reset();
        }
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
    
    public static function push($key, $value)
    {
        array_push(self::$variables[$key], $value);
    }
    
    public static function pushUnique($key, $value)
    {
        if (!in_array($value, self::$variables[$key])) {
            array_push(self::$variables[$key], $value);
        }
    }
    
    public static function remove($key, $value) {
        unset(self::$variables[$key][array_search($value, self::$variables[$key])]);
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
