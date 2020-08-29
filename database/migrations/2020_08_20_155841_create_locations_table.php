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
            $table->string('slug');
            $table->string("long_description");
            $table->string("description");
            $table->string("north")->nullable();
            $table->string("northeast")->nullable();
            $table->string("east")->nullable();
            $table->string("southeast")->nullable();
            $table->string("south")->nullable();
            $table->string("southwest")->nullable();
            $table->string("west")->nullable();
            $table->string("northwest")->nullable();
            $table->string("up")->nullable();
            $table->string("down")->nullable();
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
