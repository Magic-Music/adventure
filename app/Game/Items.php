<?php

namespace App\Game;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Item;

/**
 * Class Items
 * @package App\Game
 *
 * This class deals with handling, taking and dropping of items
 */
class Items
{
    private string $playerLocation;

    public function __construct(private Game $game) { }

    /**
     * Set the start location or character-holder of each item
     */
    public function initialise()
    {
        $items = Item::select('slug', 'location', 'character')->get();
        $itemData = [];
        foreach ($items as $item) {
            $itemData[$item->slug] = [
                'location'=> $item->location,
                'character' => $item->character
            ];
        }
        $this->game->set('itemLocations', $itemData);
        $this->playerLocation = $this->game->get('currentLocation');
    }

    /**
     * Get a collection of items at the player's location (not held by a character)
     * @return Collection
     */
    public function here() : Collection
    {
        $items = $this->game->get('itemLocations');

        $slugs = [];
        foreach ($items as $slug => $item) {
            if($item['location'] == $this->playerLocation) {
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
    public function location($item, $locationSlug = null)
    {
        if(!$locationSlug) {
            return $this->game->get('itemLocations')[$item]['location'] ?? null;
        } else {
            $itemData = $this->game->get('itemLocations');
            $itemData[$item]['location'] = $locationSlug;
            $this->game->set('itemLocations', $itemData);
        }
    }

    /**
     * Get a list of item full descriptions in the current location that have the describe_look flag set.
     */
    public function listFull(): string
    {
        return $this->game->list(
            $this->here()
                ->where('describe_look', 1)
                ->pluck('full_description')
                ->toArray()
        );
    }

    /**
     * Get a list of item short descriptions in the current location.
     * If the 'all' parameter is true, include items without the describe_look flag set
     *
     * @param bool $all When true list all items, otherwise just those with describe_look set
     */
    public function listShort($all = false): string
    {
        return $this->game->list(
            $this->here()
                ->when(!$all, function ($query) {
                    return $query->where('describe_look', 1);
                })
                ->pluck('short_description_with_article')
                ->toArray()
        );
    }

    /**
     * Attempt to take an item. Item must be in the player's
     * current location and the item takable flag must be true.
     *
     * @param ...$item array words from the player input
     * @return string response to display
     */
    public function take(...$item)
    {
        $item = trim(implode(' ', $item));
        $matchingItem = Item::where('slug', $item)
                ->orWhere('short_description', $item)
                ->orWhere('other_nouns', 'LIKE', "%|{$item}|%")
                ->first();

        if (!$matchingItem || ($matchingItem->currentLocation ?? 'no_matching_item') != $this->playerLocation) {
            return "There is no $item here";
        }

        if (!$matchingItem->takeable) {
            return "You cannot take the {$matchingItem->short_description}";
        }

        $this->location($matchingItem->slug, 'purgatory');
        $this->game->push('itemsCarried', $matchingItem->slug);
        return "You take the {$matchingItem->short_description}";
    }

    /**
     * Drop a carried item to the current location
     *
     * @param ...$item
     * @return string
     */
    public function drop(...$item)
    {
        $item = trim(implode(' ', $item));
        $matchingItem = Item::where('slug', $item)
                ->orWhere('short_description', $item)
                ->orWhere('other_nouns', 'LIKE', "%|{$item}|%")
                ->first();

        if (!$matchingItem) {
            return "You aren't carrying $item";
        }

        if (!in_array($matchingItem->slug, $this->game->get('itemsCarried'))) {
            return "You aren't carrying " . $matchingItem->short_description_with_article;
        }

        $this->game->remove('itemsCarried', $matchingItem->slug);
        $this->location($matchingItem->slug, $this->playerLocation);
        return "You drop the " . $matchingItem->short_description;
    }
}
