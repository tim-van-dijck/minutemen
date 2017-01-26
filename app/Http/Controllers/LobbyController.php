<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\Lobby;
use App\Notification;
use App\User;

class LobbyController extends Controller
{
    public function create() {
        $lobby_id = Auth::user()->hasLobby();
        if ($lobby_id) { return redirect()->route('lobbies.show', ['id' => $lobby_id]); }
        return view('lobbies.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'description'   => 'profanity-filter',
            'location_name' => 'required|profanity-filter',
            'passphrase'    => 'required_if:stealth,1|profanity-filter',
            'answer'        => 'profanity-filter',
            'stealth'       => 'required|boolean',
            'size'          => 'required|min:1|integer',
            'meet_at'       => 'required:date_format:H:i',
            'street'        => 'required',
            'number'        => 'required',
            'zip'           => 'required',
            'city'          => 'required'
        ]);

        $input = $request->except(['meet_at', 'coords']);
        $input['host_id'] = Auth::user()->id;
        $input['address'] = $input['street'].' '.$input['number'].' '.$input['zip'].' '.$input['city'];

        if ($request->input('coords')) {
            $coords = explode(';', $request->input('coords'));
            $input['lat'] = $coords[0];
            $input['long'] = $coords[1];
        }

        $meet_at = date('Y-m-d H:i:s', strtotime($request->input('meet_at')));
        if ($meet_at <= date('Y-m-d H:i:s')) { $meet_at = date('Y-m-d H:i:s', strtotime($meet_at) + 86400); }

        $lobby = new Lobby($input);
        $lobby->meet_at = $meet_at;
        $lobby->save();

        $lobby->joinLobby(Auth::user()->id);

        return redirect()->route('lobbies.show', ['id' => $lobby->id]);
    }

    public function show($id) {
        $lobby = Lobby::findOrFail($id);
        if (!$lobby->hasPlayer(Auth::user()->id)) { return redirect('dashboard'); }
        $lobby->host = User::find($lobby->host_id);
        return view('lobbies.show')->with(['lobby' => $lobby]);
    }

    public function leave($id) {
        Lobby::find($id)->leaveLobby(Auth::user()->id);
        return redirect('/dashboard');
    }

    public function destroy($id) {
        Lobby::destroy($id);
        return redirect('/dashboard');
    }

    public function invite(Request $request, $id) {
        $lobby = Lobby::find($id);
        foreach($request->input('invite') as $user_id) {
            $lobby->invite($user_id);
        }
    }

    public function acceptInvite($lobby_id, $notification_id) {
        DB::table('lobby_users')->where(['lobby_id' => $lobby_id, 'user_id' => Auth::user()->id])->update(['confirmed' => 1]);
        Notification::destroy($notification_id);
        return redirect()->route('lobbies.show', ['id' => $lobby_id]);
    }

    public function denyInvite($lobby_id, $notification_id) {
        DB::table('lobby_users')->where(['lobby_id' => $lobby_id, 'user_id' => Auth::user()->id])->delete();
        Notification::destroy($notification_id);
        return redirect()->route('users.notifications');
    }
}
