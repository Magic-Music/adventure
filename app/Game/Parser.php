<?php

namespace App\Game;

use Illuminate\Support\Arr;
use App\Models\Verb;

class Parser
{
    private static $stripWords = [
        'a',
        'an',
        'the',
        'some',
        'to',
        'me'
    ];
    
    public static function parse($command)
    {
        $words = explode(' ', $command);
        foreach ($words as $index => $word) {
            if(in_array($word, self::$stripWords)) {
                unset($words[$index]);
            }
        }
      
        $verb = $words[0];
        unset($words[0]);
        $matchVerb = Verb::where('verb', 'like', "%|{$verb}|%")->first();
        if(!$matchVerb) {
            return "Sorry, I dont understand what you mean";
        }

        if ($matchVerb->class == 'System' && !Game::developerMode()) {
            return "Sorry, I dont understand what you mean";
        }
        
        $class = '\\App\\Game\\' . $matchVerb->class;
        $function = $matchVerb->function;
        $parameters = Arr::wrap($matchVerb->parameters);
 
        $wildcard = array_search('*', $parameters);
        if($wildcard !==false) {
            array_splice($parameters, $wildcard, 1, $words);
        }
        
        return (new $class)->$function(...$parameters);
    }
}