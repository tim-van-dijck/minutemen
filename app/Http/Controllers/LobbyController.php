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
            'location_name' => 'required|profanity-filter',
            'passphrase'    => 'required_if:stealth,1|profanity-filter',
            'answer'        => 'profanity-filter',
            'stealth'       => 'required|boolean',
            'size'          => 'required|min:1|integer',
            'meet_at'       => 'required:date_format:H:i'
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

    function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
}
