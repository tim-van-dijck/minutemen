<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\Conversation;

class ConversationController extends Controller
{
    public function index() {
        return view('messages.index')->with(['conversations' => Conversation::mine()]);
    }

    public function create() {
        $conversation = new Conversation();
        $conversation->save();

        DB::table('conversation_users')->insert([
            'conversation_id'   => $conversation->id,
            'user_id'           => Auth::user()->id,
        ]);

        return redirect()->route('messages.show', ['id' => $conversation->id]);
    }

    public function show($id) {
        $conversation = Conversation::find($id);
        if (!$conversation->isRecipient()) { return redirect('/messages'); }

        $conversation->alt_title = '';
        foreach ($conversation->recipients() as $index => $recipient) {
            if ($recipient->id != Auth::user()->id) {
                if ($index > 0) { $conversation->alt_title.=', '; }
                $conversation->alt_title .= $recipient->username;
            }
        }

        return view('messages.show')->with(['conversation' => $conversation]);
    }

    public function destroy($id) {
        Conversation::destroy($id);
        return redirect()->route('conversations.index');
    }

    public function addRecipients(Request $request, $conversation_id) {
        foreach ($request->input('invite') as $user_id) {
            DB::table('conversation_users')->insert([
                'conversation_id'   => $conversation_id,
                'user_id'           => intval($user_id),
            ]);
        }
    }

    public function leaveConversation($conversation_id) {
        DB::table('conversation_users')
            ->where(['conversation_id' => $conversation_id, 'user_id' => Auth::user()->id])
            ->delete();

        return redirect()->route('conversations.index');
    }
}
