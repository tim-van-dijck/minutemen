<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_1')->unsigned();
            $table->integer('team_2')->unsigned();
            $table->integer('round_id')->unsigned();
            $table->boolean('team_1_won')->nullable()->default(null);
            $table->boolean('draw')->nullable()->default(0);

            $table->foreign('team_1')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_2')->references('id')->on('teams')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('round_id')->references('id')->on('rounds')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
