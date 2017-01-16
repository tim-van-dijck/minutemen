<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLobbyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lobby_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lobby_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('confirmed')->default(0);

            $table->foreign('lobby_id')->references('id')->on('lobbies')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lobby_users');
    }
}
