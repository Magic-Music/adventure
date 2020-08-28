<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string("full_description");
            $table->string("short_description");
            $table->string("article")->nullable();
            $table->string("other_nouns")->nullable();
            $table->string("location")->nullable();
            $table->string("character")->nullable();
            $table->boolean("takeable")->default(true);
            $table->boolean("describe_look")->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
