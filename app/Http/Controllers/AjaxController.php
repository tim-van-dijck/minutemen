<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Friendship;
use App\Game;
use App\Notification;
use App\Post;
use App\Team;
use App\User;

class AjaxController extends Controller
{
	public function freqCount() { return Friendship::getRequestCount(); }

	public function notificationCount() { return Notification::count(); }

	public function feed($id = false) { return json_encode(Post::feed($id)); }

	public function feedExtend(Request $request, $id) { return json_encode(Post::feedExpand($id, $request->input('offset'))); }

	public function joinTeam(int $team_id) { return Team::join($team_id, Auth::user()->id, false); }

	public function leaveTeam($team_id) { Team::deleteMember($team_id, Auth::user()->id); }

	public function inviteTeam($team_id, $user_id) { Team::join($team_id, $user_id, false, true); }

    public function inviteTeamBatch(Request $request, $team_id) {
	    foreach ($request->input('invite') as $user_id) {
	        Team::join($team_id, intval($user_id), true);
        }
    }
		
	public function confirmJoin($team_id, $user_id) {
		Team::confirm($team_id, $user_id);
		$team = Team::find($team_id);
		return json_encode($team->members());
	}

	public function denyRequest($team_id, $user_id) { Team::deleteRequest($team_id, $user_id); }

	public function toggleLfg() { Auth::user()->lfgToggle(); }

	public function setGameWinner(Request $request, $game_id) { Game::setWinner($game_id, $request->input('winner')); }

	public function canExpandFeed(Request $request, $id = false) { Post::canExpand($request->input('offset'), $id); }

	public function notificationSeen($notification_id) {
	    $notification = Notification::find($notification_id);
	    $notification->seen = 1;
	    $notification->save();
    }
}
