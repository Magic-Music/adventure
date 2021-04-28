<?php

namespace App\Game;

use App\Game\Game;
use App\Models\Location;

/**
 * Class System
 * @package App\Game
 *
 * This class provides system commands only available in developer mode
 * (Set developer mode in env)
 */
class System
{
    /**
     * Clear all game session variables - resets the game
     * @return string
     */
    public function flush()
    {
        Game::clear();
        return "Flushed";
    }

    /*
     * List the game session variables
     */
    public function variables()
    {
        return "<pre>" . print_r(Game::get(), true) . "</pre>";
    }

    /**
     * Teleport to the given location slug
     * @param string $slug
     * @return string
     */
    public function goToLocation($slug)
    {
        if (Location::where('slug', $slug)->first()) {
            Player::currentLocation($slug);
            return "You teleport.<br><br>" . Player::getLocationDescription(true);
        } else {
            return "Location $slug not found";
        }
    }
}
