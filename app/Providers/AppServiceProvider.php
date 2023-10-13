<?php

namespace App\Providers;

use App\Game\Ai;
use App\Game\Characters;
use App\Game\Game;
use App\Game\Items;
use App\Game\Parser;
use App\Game\Player;
use App\Game\System;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Game::class, Game::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
