<?php

namespace App\Game;

class Ai
{
    public static function run()
    {
        $characters = [];
        
        $characterResponse = implode('<br><br>' , $characters);
        
        if(strlen($characterResponse)) {
            $characterResponse = '<br><br>' . $characterResponse;
        }
        
        return $characterResponse;
    }
}