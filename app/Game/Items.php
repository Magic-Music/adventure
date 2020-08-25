<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Collection;
use App\Game\Game;
use App\Models\Item;

class Items
{
    public static function initialise()
    {
        $items = Item::select('slug', 'location', 'character')->get();
        $itemData = [];
        foreach ($items as $item) {
            $itemData[$item->slug] = [
                'location'=> $item->location,
                'character' => $item->character
            ];
        }
        Game::set('itemLocations', $itemData);
    }

    public static function here() : Collection
    {
        $items = Game::get('itemLocations');
        $location = Game::currentLocation();

        $slugs = [];
        foreach ($items as $slug => $item) {
            if($item['location'] == $location) {
                $slugs[] = $slug;
            }
        }

        return Item::wherein('slug', $slugs)->all();
    }

    public static function listFull()
    {
        return self::here()->pluck('full_description');
    }

    public static function listShort()
    {
        return self::here()->pluck('short_description');  
    }

    public static function take($item)
    {

    }
}