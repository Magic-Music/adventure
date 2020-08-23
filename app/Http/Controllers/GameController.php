<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game\Game;
use App\Game\Parser;
use App\Game\Ai;

use App\Models\Verb;

class GameController extends Controller
{
    public function command(Request $request)
    {
        $command = strtolower($request->command);
        $response = Parser::parse($command);
        $characters = Ai::run();
        $response = [
            'response' => $response . $characters . "<br>Current location is " . Game::$currentLocation,
            'gameover' => Game::$gameover                
        ];

        return response()->json($response);
    }
}
