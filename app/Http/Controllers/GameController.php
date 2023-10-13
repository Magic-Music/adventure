<?php

namespace App\Http\Controllers;

use App\Game\Characters;
use App\Game\Items;
use Illuminate\Http\Request;
use App\Game\Parser;
use App\Game\Game;
use App\Game\Ai;

class GameController extends Controller
{
    public function command(
        Ai $ai,
        Characters $characters,
        Game $game,
        Items $items,
        Parser $parser,
        Request $request,
    )
    {
        $game->initialise();
        $items->initialise();
        $characters->initialise();

        $command = strtolower($request->command);
        $response = $parser->parse($command);
        $characters = $ai->run();

        $game->save();
        $response = [
            'response' => $response . $characters,
            'gameover' => $game->gameover()
        ];

        if ($game->developerMode()) {
            $response['session'] = session('adventure');
        }

        return response()->json($response);
    }
}
