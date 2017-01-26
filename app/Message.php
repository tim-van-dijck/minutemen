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
           'conversation_id' => $data['conversation_id']
        ]);

        DB::table('conversation_users')->where('conversation_id', $data['conversation_id'])->update(['seen' => 1]);
    }
}
