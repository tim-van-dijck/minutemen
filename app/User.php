<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['username', 'firstname', 'name', 'email', 'password', 'accuracy', 'kills', 'deaths'];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [ 'password', 'remember_token', ];

	protected static function kdr() {
		$this->kdr = $this->kills / $this->deaths;
	}
}
