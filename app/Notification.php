<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Notification extends Model
{
	protected $fillable = ['content', 'seen', 'entity_name', 'entity_id', 'user_id', 'created_at', 'updated_at'];

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
        switch ($this->entity_name) {
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

    protected function newEvent($organisation_id) {
        $subscribers = User::select('users.*')
            ->join('organisation_roles', 'users.id', '=', 'organisation_roles.user_id')
            ->where([
                ['organisation_roles.organisation_id', '=', $organisation_id],
                ['organisation_roles.user_id', '!=', Auth::user()->id]
            ])->get();

        foreach ($subscribers as $subscriber) {
            self::insert([
                'content'			=> ' created a new event',
                'seen'				=> 0,
                'user_id'			=> $subscriber->id,
                'entity_name'       => 'organisation',
                'entity_id'         => $organisation_id
            ]);
        }
    }

    protected function updatedEvent($event_id) {
        $subscribers = User::select('users.*')
            ->join('team_users', 'users.id', '=', 'team_users.user_id')
            ->join('participations', 'teams.id', '=', 'participations.team_id')
            ->where([
                ['participations.event_id', '=', $event_id],
                ['team_users.pending', '=', false],
                ['team_users.deleted_at', '=', null],
            ])->get();

        foreach ($subscribers as $subscriber) {
            self::updateOrCreate([
                'content'			=> ' has been updated',
                'user_id'			=> $subscriber->id,
                'entity_name'       => 'event',
                'entity_id'         => $event_id
            ], ['seen' => 0]);
        }
    }

    protected function updatedOrganisation($organisation_id) {
        $subscribers = User::select('users.*')
            ->join('organisation_users', 'users.id', '=', 'organisation_users.user_id')
            ->where([
                ['organisation_users.organisation_id', '=', $organisation_id],
                ['organisation_users.user_id', '!=', Auth::user()->id],
            ])->get();

        foreach ($subscribers as $subscriber) {
            self::updateOrCreate([
                'content'			=> ' has been updated',
                'user_id'			=> $subscriber->id,
                'entity_name'       => 'organisation',
                'entity_id'         => $organisation_id
            ], ['seen' => 0]);
        }
    }

    protected function updatedTeam($team_id) {
        $subscribers = User::select('users.*')
            ->join('team_users', 'users.id', '=', 'team_users.user_id')
            ->where([
                ['team_users.team_id', '=', $team_id],
                ['team_users.user_id', '!=', Auth::user()->id],
                ['team_users.pending', '=', false],
                ['team_users.deleted_at', '=', null],
            ])->get();

        foreach ($subscribers as $subscriber) {
            self::updateOrCreate([
                'content'			=> ' has been updated',
                'user_id'			=> $subscriber->id,
                'entity_name'       => 'team',
                'entity_id'         => $team_id
            ],
            ['seen' => 0]);
        }
    }

    protected function message($message_id, $user_id) {
        self::insert([
            'content'			=> 'You have a new message from ',
            'seen'				=> 0,
            'user_id'			=> $user_id,
            'entity_name'       => 'message',
            'entity_id'         => $message_id
        ]);
    }

    protected function friendRequest($user_id, $freq_id) {
        self::insert([
            'content'			=> ' wants to be your friend',
            'seen'				=> 0,
            'user_id'			=> $user_id,
            'entity_name'       => 'friend-request',
            'entity_id'         => $freq_id
        ]);
    }

    protected function lobbyInvite($user_id, $inviter_id, $lobby_id) {
        $inviter = User::find($inviter_id);
        self::insert([
            'content'			=> '<a href="'.route('users.show', ['id' => $lobby_id]).'">'.$inviter->username.'</a> wants to be your friend',
            'seen'				=> 0,
            'user_id'			=> $user_id,
            'entity_name'       => 'lobby-invite',
            'entity_id'         => $lobby_id
        ]);
    }

    protected function count() {
        return count(Notification::select('id')->where(['user_id' => Auth::user()->id, 'seen' => 0])->get());
    }
}
