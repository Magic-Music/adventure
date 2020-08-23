<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string("long_description");
            $table->string("description");
            $table->integer("north")->nullable();
            $table->integer("northeast")->nullable();
            $table->integer("east")->nullable();
            $table->integer("southeast")->nullable();
            $table->integer("south")->nullable();
            $table->integer("southwest")->nullable();
            $table->integer("west")->nullable();
            $table->integer("northwest")->nullable();
            $table->integer("up")->nullable();
            $table->integer("down")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
