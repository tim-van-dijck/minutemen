<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Friendship;
use App\Post;
use App\User;

class AjaxController extends Controller
{
    public function notificationCount() {
    	return Friendship::getRequestCount();
    }

    public function feed($id = false) {
    	return json_encode(Post::feed($id));
    }
}
