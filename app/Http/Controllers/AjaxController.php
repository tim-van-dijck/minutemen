<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Friendship;
use App\User;

class AjaxController extends Controller
{
    public function notificationCount() {
    	return Friendship::getRequestCount();
    }
}
