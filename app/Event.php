<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use Image;

class Event extends Model
{
    public $timestamps = false;
    protected $fillable = ['title', 'description', 'starts_at', 'ends_at', 'street', 'number', 'zip', 'city', 'banner', 'coords', 'organisation_id'];

	public function participators() {
		return Team::select('*')->join('participations', 'participations.team_id', '=', 'teams.id')->where('participations.event_id', $this->id)->get();
	}

	public function full() {
		return count($this->participators()) == $this->max_teams;
	}

	protected function enter($event_id, $team_id) {
		$event = self::find($event_id);
		if (!$event->full()) {
			$participation = DB::table('participations')->where(['event_id' => $event_id, 'team_id' => $team_id])->first();
			if (!isset($participation->event_id)) {
				DB::table('participations')->insert([
					'event_id' => $event_id,
					'team_id' => $team_id
				]);
				return true;
			}
		}
		return false;
	}
}
