<?php

namespace App\Game;

use App\Game\Items;
use App\Game\Characters;
use App\Models\Location;

/**
 * Class Game
 * @package App\Game
 *
 * This class is used for all the game master functions:-
 *      Starting/ending a game
 *      In-game variable storage
 *      Developer mode (set in .env)
 *      Helper functions
 */
class Game
{
    /**
     * @var array In game variables, stored in session
     */
    public $variables;

    /**
     * Wipe all in-game variables
     */
    public function clear()
    {
        session()->flush();
        $this->variables = null;
    }

    /**
     * Start a new game
     * Set in-game variables to default values
     */
    private function reset()
    {
        //If not configured in the env file, start at location with id 1
        $startLocationSlug = config('adventure.start_location_slug') ?: (Location::find(1)->slug);
        $developerMode = config('adventure.developer_mode');

        $this->variables = [
            'developerMode' => $developerMode,
            'currentLocation' => $startLocationSlug,
            'gameover' => false,
            'itemLocations' => [],
            'itemsCarried' => [],
            'characterLocations' => [],
            'locationsVisited' => [],
        ];
    }

    /**
     * Start of a round
     * Load in-game variables from session
     */
    public function initialise()
    {
        $this->variables = session('adventure') ?? null;
        if(!$this->variables) {
            $this->reset();
        }
    }

    /**
     * End of a round
     * Store in-game variables to session
     */
    public function save()
    {
        session(['adventure' => $this->variables]);
    }

    /**
     * Get the value of a specified in-game variable,
     * or all variables if key is not specified
     * @param string|null $key
     * @return array|mixed|null
     */
    public function get($key = null)
    {
        return ($key == null) ? $this->variables : ($this->variables[$key] ?? null);
    }

    /**
     * Set an in-game variable
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    /**
     * Add a value to an in-game array
     * @param string $key
     * @param mixed $value
     */
    public function push($key, $value)
    {
        array_push($this->variables[$key], $value);
    }

    /**
     * Add a value to an in-game variable, de-duplicated
     * @param string $key
     * @param mixed $value
     */
    public function pushUnique($key, $value)
    {
        if (!in_array($value, $this->variables[$key])) {
            array_push($this->variables[$key], $value);
        }
    }

    /**
     * Remove an in-game variable
     * @param string $key
     * @param mixed $value
     */
    public function remove($key, $value) {
        unset($this->variables[$key][array_search($value, $this->variables[$key])]);
    }

    /**
     * Get or set the game-over status
     * @param string $update if not 'no' use this value
     * @return string|void The game-over status ('no' while game is running)
     */
    public function gameover($update = 'no')
    {
        if($update != 'no') {
            $this->set('gameover', $update);
        } else {
            return $this->get('gameover');
        }
    }

    /**
     * Implode an array with commas, except last two elements conjoined with 'and'
     * e.g. ['a yak', 'a gnu', 'a moose', 'an elk']
     * returns 'a yak, a gnu, a moose and an elk'
     *
     * @param $array
     * @return string
     */
    public function list($array)
    {
        $list = implode(', ', $array);
        if(count($array) > 1) {
            $list = preg_replace("((.*),)", "$1 and", $list);
        }
        return $list;
    }

    /**
     * Get the developer mode status
     * @return bool
     */
    public function developerMode()
    {
        return $this->get('developerMode');
    }
}
