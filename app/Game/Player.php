<?php

namespace App\Game;

use App\Game\Game;
use App\Models\Item;
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
    
    public static function currentLocation($update = false)
    {
        if($update !== false) {
            Game::set('currentLocation', $update);
        } else {
            return Game::get('currentLocation');
        }
    }
    
    public function go($params)
    {
        $direction = $this->directions[$params[0]] ?? $params[0];
        return $this->move([$direction]);
    }
    
    public function move($params)
    {
        $direction = $params[0];
        $location = Location::where('slug', self::currentLocation())->first();
        $newLocation = $location->$direction;
        
        if ($newLocation == '' || is_null($newLocation)) {
            return "You cannot go $direction.";
        }
        
        self::currentLocation($newLocation);   
        return "You go $direction.<br><br>" . $this->look();
    }
    
    public function goToLocation($slug)
    {
        self::currentLocation($slug);   
        return ($this->look());        
    }
    
    public function look($params = null)
    {
        $currentLocation = self::currentLocation();
        $visitedPreviously = in_array($currentLocation, Game::get('locationsVisited'));
        if (($params[0] ?? null) == 'full') {
            $visitedPreviously = false;
        }
        $position = Location::where('slug', $currentLocation)->first();
        if (!$visitedPreviously) {
            $location =  $position->long_description;
            $itemList = Game::list(Items::listShort());
            Game::pushUnique('locationsVisited', $currentLocation);
        } else {
            $exits = implode(',', $position->exits);     
            $location = $position->description . " Exits are " . $exits;
            $itemList = Game::list(Items::listShort(true));
        }
        $items = ($itemList) ? "<br><br>You can see $itemList." : '';
        return "You are " . $location . $items . "<br><br>";
    }
    
    public function inventory()
    {
        $carried = Game::get('itemsCarried');
        if(!$carried) {
            return "You aren't carrying anything.";
        } else {
            $items = Item::wherein('slug', $carried)
                    ->get()
                    ->pluck('short_description_with_capitalised_article')
                    ->toArray();
            
            return "You are carrying " . Game::list($items);
        }
    }
}