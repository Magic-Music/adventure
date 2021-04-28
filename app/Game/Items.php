<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Collection;
use App\Game\Game;
use App\Game\Player;
use App\Models\Item;

/**
 * Class Items
 * @package App\Game
 *
 * This class deals with handling, taking and dropping of items
 */
class Items
{
    /**
     * Set the start location or character-holder of each item
     */
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

    /**
     * Get a collection of items at the player's location (not held by a character)
     * @return Collection
     */
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

    /**
     * Get or set an item's location
     * @param string $item slug of the item
     * @param string|null $locationSlug slug of the location to set or null to get
     * @return string|null|void location slug if getting
     */
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

    /**
     * Get a list of item full descriptions in the current location that have the describe_look flag set.
     * @return array
     */
    public static function listFull()
    {
        return self::here()
                ->where('describe_look', 1)
                ->pluck('full_description')
                ->toArray();
    }

    /**
     * Get a list of item short descriptions in the current location.
     * If the 'all' parameter is true, include items without the describe_look flag set
     *
     * @param bool $all When true list all items, otherwise just those with describe_look set
     * @return array
     */
    public static function listShort($all = false)
    {
        return self::here()
                ->when(!$all, function ($query) {
                    return $query->where('describe_look', 1);
                })
                ->pluck('short_description_with_article')
                ->toArray();
    }

    /**
     * Attempt to take an item. Item must be in the player's
     * current location and the item takable flag must be true.
     *
     * @param ...$item array words from the player input
     * @return string response to display
     */
    public static function take(...$item)
    {
        $item = trim(implode(' ', $item));
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

    /**
     * Drop a carried item to the current location
     *
     * @param ...$item
     * @return string
     */
    public static function drop(...$item)
    {
        $item = trim(implode(' ', $item));
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
