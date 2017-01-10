<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Conversation;

class ConversationController extends Controller
{
    public function index() {
        return view('messages.index')->with(['conversations' => Conversation::mine()]);
    }

    public function create() {
        $conversation = new Conversation();
        $conversation->title = '<No recipients>';
        $conversation->save();

        return redirect()->route('messages.show', ['id' => $conversation->id]);
    }

    public function show($id) {
        return view('messages.show')->with(['conversation' => Conversation::find($id)]);
    }

    public function inviteToConversation($conversation_id, $user_id) {
        DB::table('conversation_users')->insert([
            'conversation_id'   => $conversation_id,
            'user_id'           => $user_id,
        ]);
    }

    public function leaveConversation($conversation_id) {
        DB::table('conversation_users')
            ->where(['conversation_id' => $conversation_id, 'user_id' => Auth::user()->id])
            ->delete();
    }
}
