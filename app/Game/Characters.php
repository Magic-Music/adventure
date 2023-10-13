<?php


namespace App\Game;

use App\Models\Character;


class Characters
{
    public function __construct(private Game $game) { }

    public function initialise()
    {
        $characters = Character::select('slug', 'location')->get();
        $characterLocations = [];
        foreach ($characters as $character) {
            $characterLocations[$character->slug] = $character->location;
        }
        $this->game->set('characterLocations', $characterLocations);
    }

    public static function getAllCharacterSlugs()
    {
        return Character::select('slug')->pluck('slug')->toArray();
    }

    public static function isHere($characterSlug): array
    {
        //TODO - logic to return an array of characters in current location
        return [];
    }
}
