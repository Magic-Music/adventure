<?php

namespace App\Game;

use App\Game\Game;
use App\Models\Item;
use App\Models\Location;

/**
 * Class Player
 * @package App\Game
 *
 * This class deals with actions carried out by the player:
 *      Moving to a new location
 *      Looking around
 *      Checking item inventory
 */
class Player
{
    //Direction command shortcuts
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

    /**
     * Get or set the player location
     *
     * @param false $update When true set the player location to the given location slug
     * @return string|void When getting, returns the current location slug
     */
    public static function currentLocation($update = false)
    {
        if($update !== false) {
            Game::set('currentLocation', $update);
        } else {
            return Game::get('currentLocation');
        }
    }

    /**
     * Player hasn't pressed a key for a while, so we'll return an empty
     * response so the AI characters can do their thing
     * @return string
     */
    public function nothing()
    {
        return "You wait. Time passes...";
    }

    /**
     * Translate shortcut direction command if necessary
     * @param string $direction
     * @return string
     */
    public function go($direction)
    {
        $direction = $this->directions[$direction] ?? $direction;
        return $this->move($direction);
    }

    /**
     * Move to a new location if possible
     * @param string $direction
     * @return string
     */
    public function move($direction)
    {
        $location = Location::where('slug', self::currentLocation())->first();
        $newLocation = $location->$direction;

        if ($newLocation == '' || is_null($newLocation)) {
            return "You cannot go $direction.";
        }

        self::currentLocation($newLocation);
        return "You go $direction.<br><br>" . $this->look();
    }

    /**
     * List carried items
     * @return string
     */
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

    /**
     * Redisplay the location description
     * @param string $fullDescription set to 'full' for full description
     * @return string
     */
    public function look($fullDescription = null)
    {
        return self::getLocationDescription($fullDescription);
    }

    /**
     * Get a full or short location and item description
     * @param null $fullDescription
     * @return string
     */
    public static function getLocationDescription($fullDescription = null)
    {
        $currentLocation = self::currentLocation();

        //By default once a location has been visited, we'll only display the
        //short description. This is overridden with the fullDescription flag
        $visitedPreviously = in_array($currentLocation, Game::get('locationsVisited'));
        if ($fullDescription == 'full') {
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
}
