<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('slug')->unique();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();

            $table->string('email')->unique();
            $table->string('password');
            
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();

            $table->string('img')->nullable();
            $table->boolean('lfg')->default(false);

            $table->boolean('admin')->default(0);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}