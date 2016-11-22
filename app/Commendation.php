<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Commendation extends Model
{
    public $timestamps = false;
	protected $fillable = ['user_id', 'commendee_id'];

	protected function add($id) {
		if (!self::exists($id)) {
			$commendation = new Commendation(['user_id' => Auth::user()->id, 'commendee_id' => $id]);
		}
	}

	protected function exists($id) {
		try {
			self::where('user_id', Auth::user()->id)->where('commendee_id', $id)->firstOrFail();
	    	return true;
		} catch (ErrorException $e) {
			return false;
		}
	}

	protected function count($id = false) {
		if (!$id) { $id = Auth::user()->id; }

		return self::select(DB::raw('count(commendee_id) as count'))->where('commendee_id', $id)->groupBy('commendee_id')->value('count');
	}
}
