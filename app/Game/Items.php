<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Collection;
use App\Game\Game;
use App\Game\Player;
use App\Models\Item;

class Items
{
    public static function initialise()
    {
        error_log("Items initialise");
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
        $location = Player::currentLocation();

        $slugs = [];
        foreach ($items as $slug => $item) {
            if($item['location'] == $location) {
                $slugs[] = $slug;
            }
        }

        return Item::wherein('slug', $slugs)->get();
    }
    
    public static function location($item, $locationSlug = null)
    {
        if(!$locationSlug) {
            return Game::get('itemLocations')[$item]['location'] ?? null;
        } else {
            $itemData = Game::get('itemLocations');
            $itemData[$item]['location'] = $locationSlug;
            Game::set('itemLocations', $itemData);
        }
    }

    public static function listFull()
    {
        return self::here()
                ->where('describe_look', 1)
                ->pluck('full_description')
                ->toArray();  
    }

    public static function listShort($all = false)
    {
        return self::here()
                ->when(!$all, function ($query) {
                    return $query->where('describe_look', 1);
                })                        
                ->pluck('short_description_with_article')
                ->toArray();  
    }

    public static function take($params)
    {
        $item = trim(implode(' ', $params));
        $matchingItem = Item::where('slug', $item)
                ->orWhere('short_description', $item)
                ->orWhere('other_nouns', 'LIKE', "%|{$item}|%")
                ->first();
        
        if (!$matchingItem || ($matchingItem->currentLocation ?? 'no_matching_item') != Player::currentLocation()) {
            return "There is no $item here";
        }
        
        if (!$matchingItem->takeable) {
            return "You cannot take the {$matchingItem->short_description}";
        }
        
        self::location($matchingItem->slug, 'purgatory');
        Game::push('itemsCarried', $matchingItem->slug);
        return "You take the {$matchingItem->short_description}";
    }
    
    public static function drop($params)
    {
        $item = trim(implode(' ', $params));
        $matchingItem = Item::where('slug', $item)
                ->orWhere('short_description', $item)
                ->orWhere('other_nouns', 'LIKE', "%|{$item}|%")
                ->first();
        
        if (!$matchingItem) {
            return "You aren't carrying $item";
        }
        
        if (!in_array($matchingItem->slug, Game::get('itemsCarried'))) {
            return "You aren't carrying " . $matchingItem->short_description_with_article;
        }
        
        Game::remove('itemsCarried', $matchingItem->slug);
        self::location($matchingItem->slug, Player::currentLocation());
        return "You drop the " . $matchingItem->short_description;
    }
}