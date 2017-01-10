<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LobbyController extends Controller
{
    public function create() {
        return view('lobbies.create');
    }

    public function store(Request $request) {
        $this->validate($request, [
            'location_name' => 'required|profanity-filter',
            'passphrase'    => 'required_if:stealth,1|profanity-filter',
            'passphrase'    => 'profanity-filter',
            'stealth'       => 'required|boolean',
            'size'          => 'required|min:1|integer'
        ]);

        $input = $request->all();

        $lobby = new Lobby($input);
        $lobby->save();

        return redirect()->route('lobbies.show', ['id' => $lobby->id]);
    }

    public function show($id) {
        return view('lobbies.show')->with(['lobby' => Lobby::find($id)]);
    }

    public function destroy($id) { Lobby::destroy($id); }
}
