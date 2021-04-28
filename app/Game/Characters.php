<?php


namespace App\Game;

use App\Models\Character;


class Characters
{
    public static function initialise()
    {
        $characters = Character::select('slug', 'location')->get();
        $characterLocations = [];
        foreach ($characters as $character) {
            $characterLocations[$character->slug] = $character->location;
        }
        Game::set('characterLocations', $characterLocations);
    }

    public static function getAllCharacterSlugs()
    {
        return Character::select('slug')->pluck('slug')->toArray();
    }

    public static function isHere($characterSlug)
    {
        return $here;
    }
}
