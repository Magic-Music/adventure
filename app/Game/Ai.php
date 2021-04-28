<?php

namespace App\Game;

use App\Game\Characters;

class Ai
{
    public static function run()
    {
        $responses = [];
        $characters = Characters::getAllCharacterSlugs();

        foreach ($characters as $character) {
            $actions = [];
            if (Characters::isHere($character)) {
                $actions = array_filter(rand(0,1) ? [$this->do(), $this->say()] : [$this->say(), $this->do());
                $actions[] = $this->go();
            } else {
                $do = $this->do();
                $this->go();
                if (Characters::isHere($character)) {
                    $actions[] = " arrives";
                }
                $actions[] = $this->say();
            }

        }

        $characterResponse = implode('<br><br>' , $responses);

        if(strlen($characterResponse)) {
            $characterResponse = '<br><br>' . $characterResponse;
        }

        return $characterResponse;
    }
}
