<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Conversation extends Model
{
    protected $fillable = ['title', 'created_at', 'updated_at'];

    public function messages() {
        $messages = Message::where('conversation_id', $this->id)->orderBy('created_at')->get();

        foreach ($messages as $message) {
            $message->sender = User::find($message->sender_id);
        }

        return $messages;
    }

    public function recipients() {
        return User::select('users.*')
                    ->join('conversation_users', 'conversation_users.user_id', '=', 'users.id')
                    ->where('conversation_users.conversation_id', $this->id)
                    ->get();
    }

    protected function mine() {
        return Conversation::join('conversation_users', 'conversations.id', '=', 'conversation_users.conversation_id')
            ->where('conversation_users.user_id', Auth::user()->id)->get();
    }
}
