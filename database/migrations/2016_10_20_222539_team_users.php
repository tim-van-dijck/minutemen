<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TeamUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_users', function (Blueprint $table) {
            $table->integer('team_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('admin')->default(false);
            $table->boolean('pending')->default(true);
            $table->timestamp('created_at')->useCurrent();

            $table->softDeletes();
            
            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('team_users');
    }
}
