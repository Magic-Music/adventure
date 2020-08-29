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
    
    public function look($params = null)
    {
        $currentLocation = Game::currentLocation();
        $visitedPreviously = in_array($currentLocation, Game::get('locationsVisited'));
        if (($params[0] ?? null) == 'full') {
            $visitedPreviously = false;
        }
        $position = Location::where('slug', $currentLocation)->first();
        if (!$visitedPreviously) {
            $location =  $position->long_description;
            $itemList = implode(', ', Items::listShort());
            Game::pushUnique('locationsVisited', $currentLocation);
        } else {
            $exits = implode(',', $position->exits);     
            $location = $position->description . " Exits are " . $exits;
            $itemList = implode(', ', Items::listShort(true));
        }
        $items = ($itemList) ? "<br><br>You can see: $itemList" : '';
        return "You are " . $location . $items . "<br><br>";
    }
    
    public function inventory()
    {
        $carried = Game::get('itemsCarried');
        if(!$carried) {
            return "You aren't carrying anything";
        } else {
            $items = Item::wherein('slug', $carried)
                    ->get()
                    ->pluck('short_description_with_capitalised_article')
                    ->toArray();
            
            return "You are carrying:<br>" . implode('<br>', $items);
        }
    }
}