<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Conversation extends Model
{
    protected $fillable = ['title', 'created_at', 'updated_at'];

    public function messages() {
        $messages = Message::where('conversation_id', $this->id)->orderBy('created_at', 'desc')->limit(50)->get();

        $messages->reverse();

        foreach ($messages as $message) {
            $message->sender = User::find($message->sender_id);
            $message->content = nl2br($message->content);
            $message->own = ($message->sender_id == Auth::user()->id);
        }

        return $messages;
    }

    public function latestMessage() {
        return Message::where('conversation_id', $this->id)->orderBy('created_at', 'desc')->first();
    }

    public function recipients() {
        return User::select('users.*')
                    ->join('conversation_users', 'conversation_users.user_id', '=', 'users.id')
                    ->where('conversation_users.conversation_id', $this->id)
                    ->get();
    }

    public function isRecipient() {
        return DB::table('conversation_users')
                    ->where(['conversation_id' => $this->id, 'user_id' => Auth::user()->id])
                    ->exists();
    }

    protected function mine() {
        $conversations =  Conversation::select('conversations.*')->join('conversation_users', 'conversations.id', '=', 'conversation_users.conversation_id')
            ->where('conversation_users.user_id', Auth::user()->id)->get();

        foreach ($conversations as $conversation) {
            $conversation->alt_title = '';
            foreach ($conversation->recipients() as $index => $recipient) {
                if ($recipient->id != Auth::user()->id) {
                    if ($index > 0 && $conversation->alt_title != '') { $conversation->alt_title.=', '; }
                    $conversation->alt_title .= $recipient->username;
                }
            }
        }

        return $conversations;
    }

    protected function countUnseen() {
        $messages = DB::table('conversation_users')
                        ->select('id')
                        ->where('conversation_users.seen', 0)
                        ->where('conversation_users.user_id', Auth::user()->id)
                        ->get();
        return count($messages);
    }
}
