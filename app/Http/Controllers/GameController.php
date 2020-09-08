<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game\Parser;
use App\Game\Game;
use App\Game\Ai;

class GameController extends Controller
{
    public function command(Request $request)
    {
        Game::initialise();
        
        $command = strtolower($request->command);
        $response = Parser::parse($command);
        $characters = Ai::run();
        
        Game::save();
        $response = [
            'response' => $response . $characters,
            'gameover' => Game::gameover()
        ];
        
        if (Game::developerMode()) {
            $response['session'] = session('adventure');
        }

        return response()->json($response);
    }
}
