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
                $actions = array_filter(rand(0,1) ? [self::do(), self::say()] : [self::say(), self::do()]);
                $actions[] = self::go();
            } else {
                $do = self::do();
                self::go();
                if (Characters::isHere($character)) {
                    $actions[] = " arrives";
                }
                $actions[] = self::say();
            }

        }

        $characterResponse = implode('<br><br>' , $responses);

        if(strlen($characterResponse)) {
            $characterResponse = '<br><br>' . $characterResponse;
        }

        return $characterResponse;
    }

    private static function say(): string
    {
        return '';
    }

    private static function do(): string
    {
        return '';
    }

    private static function go(): string
    {
        return '';
    }
}
