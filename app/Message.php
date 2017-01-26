<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Message extends Model
{
    protected $fillable = ['content', 'sender_id', 'conversation_id', 'created_at', 'updated_At'];

    protected function make($data) {
        self::insert([
            'content' => $data['content'],
            'sender_id' => $data['sender_id'],
            'conversation_id' => $data['conversation_id'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('conversation_users')
            ->where([
                'conversation_id' => $data['conversation_id'],
                'user_id' => $data['sender_id']
            ])->update(['conversation_users.seen' => 0]);
    }
}
