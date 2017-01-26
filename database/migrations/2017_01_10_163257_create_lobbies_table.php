<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLobbiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lobbies', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description')->nullable();
            $table->string('location_name');
            $table->string('address')->nullable();
            $table->double('lat', 9, 6);
            $table->double('long', 9, 6);
            $table->timestamp('meet_at');
            $table->string('stealth')->nullable();
            $table->string('passphrase');
            $table->string('answer')->nullable();
            $table->integer('size');
            $table->integer('host_id')->unsigned();

            $table->foreign('host_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lobbies');
    }
}
