<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
	public $timestamps = false;
	protected $fillable = ['event_id', 'name'];
	
    protected function make() {
    	// 
    }
}
