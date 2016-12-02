<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Image;

class Event extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'starts_at', 'ends_at', 'street', 'number', 'zip', 'city', 'banner', 'coords', 'organisation_id'];

    protected function uploadImg($file) {
    	$exists = true;
		$hash = '';

		while ($exists) {
			$hash = 'img/events/'.hash('sha512', str_random(40));

			if (is_string($file)) { $hash.='.png'; }
			else { $hash.='.'.$file->getClientOriginalExtension(); }

			$exists = file_exists(public_path($hash));
		}

		if (!is_string($file)) { $file = $file->getRealPath(); }

		$img = Image::make($file)
				->resize(250, null, function ($constraint) {
					$constraint->aspectRatio();
					$constraint->upsize();
				})->save(public_path($hash));

		return $hash;
	}

	protected function enter($event_id, $team_id) {
		$participation = DB::table('participations')->where(['event_id' => $event_id, 'team_id' => $team_id])->first();
		if (!isset($participation->event_id)) {
			DB::table('participations')->insert([
				'event_id' => $event_id,
				'team_id' => $team_id
			]);
			return true;
		}
		return false;
	}
}
