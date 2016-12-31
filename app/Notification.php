<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Notification extends Model
{
	protected $fillable = ['content', 'seen', 'user_id', 'created_at', 'updated_at'];

    protected function post($entity_id, $entity) {
        switch ($entity) {
            case 'event':
                $table = 'participations';
                $id = 'event_id';
                break;
            case 'team':
                $table = 'team_users';
                $id = 'team_id';
                break;
            case 'organisation':
                $table = 'organisation_roles';
                $id = 'organisation_id';
                break;
            default:
                break;
        }

        $subscribers = User::select('users.*')
                            ->join($table, 'users.id', '=', $table.'.user_id')
                            ->where([
                                [$table.'.'.$id, '=', $entity_id],
                                [$table.'.user_id', '!=', Auth::user()->id]
                            ])->get();

		foreach ($subscribers as $subscriber) {
			self::insert([
				'content'			=> ' has a new post',
				'seen'				=> 0,
				'user_id'			=> $subscriber->id,
                'entity_name'       => $entity,
                'entity_id'         => $entity_id
			]);
		}
	}

	public function entity() {
        switch ($this->entity) {
            case 'team':
                return Team::find($this->entity_id);
                break;
            case 'organisation':
                return Organisation::find($this->entity_id);
                break;
            case 'event':
                return Event::find($this->entity_id);
                break;
            default:
                return null;
                break;
        }
    }
}
