<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function command(Request $request)
    {
        $command = strtolower($request->command);
        
        return response()->json([
            'response' => "You $command",
            'gameover' => true
        ]);
    }
}
