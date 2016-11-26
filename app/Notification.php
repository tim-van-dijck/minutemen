<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $fillable = ['content', 'seen', 'organisation_id', 'user_id'];

    protected function send($organisation_id) {
		$subscribers = User::select('*')
							->join('organisation_roles', 'users.id', '=', 'organisation_roles.user_id')
							->where('organisation_roles.organisation_id', $organisation_id)
							->get();

		foreach ($subscribers as $subscriber) {
			self->insert([
				'content'			=> 'posted an update',
				'seen'				=> 0,
				'organisation_id'	=> $organisation_id,
				'user_id'			=> $subscriber->id,
			]);
		}
	}

	protected function getExtended($user_id) {
		$notifications = self::where('user_id', $user_id)->get();
		foreach ($notifications as $notification) {
			$organisation = Organisation::find($notification->organisation_id);
			$notification->content = $organisation->name .= ' '.$notification->content;
		}
	}
}
