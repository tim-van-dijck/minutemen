<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Lobby extends Model
{
    public $timestamps = false;
    protected $fillable = ['description', 'location_name', 'address', 'lat', 'long', 'meet_at', 'stealth', 'passphrase', 'answer', 'size', 'host_id'];

    public function players() {
        return self::select('users.id', 'users.username', 'users.slug')
                    ->join('lobby_users', 'lobby_users.lobby_id', '=', 'lobbies.id')
                    ->join('users', 'lobby_users.user_id', '=', 'users.id')
                    ->where('lobbies.id', $this->id)
                    ->where('confirmed', 1)
                    ->orderBy('lobby_users.id', 'asc')
                    ->get();
    }


    public function playerCount() {
        return self::select('users.id')
                    ->join('lobby_users', 'lobby_users.lobby_id', '=', 'lobbies.id')
                    ->join('users', 'lobby_users.user_id', '=', 'users.id')
                    ->where('lobbies.id', $this->id)
                    ->where('confirmed', 1)
                    ->count();
    }

    public function hasPlayer($user_id) {
        return self::select('user_id')
            ->join('lobby_users', 'lobby_users.lobby_id', '=', 'lobbies.id')
            ->where('lobby_users.lobby_id', $this->id)
            ->where('lobby_users.user_id', $user_id)
            ->where('confirmed', 1)
            ->exists();
    }

    public function joinLobby($user_id) {
        $lobby_user = DB::table('lobby_users')->where([
            'lobby_id'  => $this->id,
            'user_id'  => $user_id,
        ])->first();

        if ($lobby_user == null) {
            DB::table('lobby_users')->insert([
                'lobby_id'  => $this->id,
                'user_id'  => $user_id,
            ]);
            User::find($user_id)->nlfg();
        }
    }

    public function leaveLobby($user_id) {
        DB::table('lobby_users')->where([
            'lobby_id'  => $this->id,
            'user_id'  => $user_id
        ], [])->delete();
    }

    public function invite($user_id) {
        DB::table('lobby_users')->insert([
            'lobby_id'  => $this->id,
            'user_id'   => $user_id,
            'confirmed' => 0
        ]);

        Notification::lobbyInvite($user_id, Auth::user()->id, $this->id);
    }
}
