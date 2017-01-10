<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

use App\Conversation;
use App\Message;
use App\User;

class MessageController extends Controller
{
    public function send(Request $request, $conversation_id) {
        $input = $request->all();

        $recipients = Conversation::find($conversation_id)->recipients();
        if ($recipients->isEmpty()) {
            $recipients = $input['recipients'];
        }
        $data = [
            'content'           => $input['message'],
            'sender_id'         => Auth::user()->id,
            'recipients'        => $recipients,
            'conversation_id'   => $conversation_id
        ];

        Message::make($data);
    }

    public function getByConversation($conversation_id) {
        $messages = Conversation::find($conversation_id)->messages();

        foreach ($messages as $message) {
            $message->sender = User::find($message->sender_id);
        }
        return json_encode($messages);
    }
}