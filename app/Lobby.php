<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lobby extends Model
{
    public $timestamps = false;
    protected $fillable = ['location_name', 'address', 'coords', 'passphrase', 'answer', 'size', 'host_id'];

}
