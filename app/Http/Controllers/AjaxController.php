<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\Friendship;
use App\Game;
use App\Lobby;
use App\Notification;
use App\Post;
use App\Team;

class AjaxController extends Controller
{
    // Notification functions
	public function freqCount() { return Friendship::getRequestCount(); }

	public function notificationCount() { return Notification::count(); }

    public function notificationSeen($notification_id) {
        $notification = Notification::find($notification_id);
        $notification->seen = 1;
        $notification->save();
    }

    // Feed fucntions
	public function feed($id = false) { return json_encode(Post::feed($id)); }

	public function feedExtend(Request $request, $id) { return json_encode(Post::feedExpand($id, $request->input('offset'))); }

    public function canExpandFeed(Request $request, $id = false) { Post::canExpand($request->input('offset'), $id); }

    // Team functions
	public function joinTeam($team_id) { return Team::join($team_id, Auth::user()->id, false); }

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

	// LFG & Lobby functions
	public function toggleLfg() { Auth::user()->lfgToggle(); }

	public function findLobby() {
	    $lobbies = DB::table('lobbies')->select('lobbies.*', DB::raw('COUNT(lobby_users.lobby_id) AS players'))
                        ->join('lobby_users', 'lobby_users.lobby_id', '=', 'lobbies.id')
                        ->groupBy('lobby_users.lobby_id')
                        ->havingRaw('players < size')
                        ->orderBy('players')
	                    ->get();

//        SELECT lobbies.*, COUNT(lobby_users.lobby_id) AS players FROM lobbies
//        JOIN lobby_users ON lobby_users.lobby_id = lobbies.id GROUP BY lobby_users.lobby_id HAVING players < size;

        if ($lobbies->isEmpty()) { return json_encode(['error' => 'No suitable lobby could be found']); }

        $lobby = Lobby::find($lobbies->random()->id);
        $lobby->joinLobby(Auth::user()->id);

        return json_encode(['success' => 1, 'link' => route('lobbies.show', ['id' => $lobby->id])]);
    }

	public function getLobbyPlayers($lobby_id) { return json_encode(Lobby::find($lobby_id)->players()); }

	public function getLobbyPlayerCount($lobby_id) {
	    $lobby = Lobby::find($lobby_id);
	    if ($lobby == null) { return json_encode(['error' => 'deleted']); }
	    return json_encode(['count' => $lobby->playerCount()]); }

	// Event & Game functions
	public function setGameWinner(Request $request, $game_id) { Game::setWinner($game_id, $request->input('winner')); }
}
