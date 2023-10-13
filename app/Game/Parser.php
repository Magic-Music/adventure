<?php

namespace App\Game;

use Illuminate\Support\Arr;
use App\Models\Verb;

/**
 * Class Parser
 * @package App\Game
 *
 * This class takes the player input and attempts to work out which class and function to pass it to.
 * The input is split into individual words, and un-required stripWords removed
 * The first word is taken as a verb, and looked up in the verb table to get the matching class and function
 * The rest of the words are passed to the class->function
 */
class Parser
{
    private $stripWords = [
        'a',
        'an',
        'the',
        'some',
        'to',
        'me'
    ];

    public function __construct(private Game $game) { }

    /**
     * Parse the player input
     * @param string $command
     * @return string The response to display to the player
     */
    public function parse($command)
    {
        $words = explode(' ', $command);

        //Remove any stripWords
        foreach ($words as $index => $word) {
            if(in_array($word, $this->stripWords)) {
                unset($words[$index]);
            }
        }

        //Look up the first word in the verb table
        $verb = $words[0];
        unset($words[0]);
        $matchVerb = Verb::where('verb', 'like', "%|{$verb}|%")->first();
        if(!$matchVerb) {
            return "Sorry, I dont understand what you mean";
        }

        //System functions can only be invoked in developer mode
        if ($matchVerb->class == 'System' && !$this->game->developerMode()) {
            return "Sorry, I dont understand what you mean";
        }

        //Get the class and function for the verb
        $class = '\\App\\Game\\' . $matchVerb->class;
        $function = $matchVerb->function;

        //Parameters to pass to the function may be stored in the verb table
        $parameters = Arr::wrap($matchVerb->parameters);

        //If any of the parameters in the verb table is '*', that parameter
        //is replaced with the remaining words from the player input
        $wildcard = array_search('*', $parameters);
        if($wildcard !==false) {
            array_splice($parameters, $wildcard, 1, $words);
        }

        //Invoke the class and function, pass the parameters, return the response
        return app($class)->$function(...$parameters);
    }
}
