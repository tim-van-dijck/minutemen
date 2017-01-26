<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Post extends Model
{
	protected $fillable = ['content', 'created_at', 'updated_at', 'organisation_id'];

	protected function make($data) {
		$data['content'] = strip_tags(nl2br($data['content']), '<br><a>');
		
		$post = new Post($data);
		$post->save();

		Notification::post($data['organisation_id'], 'organisation');
	}

	protected function getByUser($user_id, $offset = false) {
		$query = self::select('posts.*')->join('organisation_roles', 'organisation_roles.organisation_id', '=', 'posts.organisation_id')
					->where('organisation_roles.user_id', $user_id)->orderBy('posts.created_at', 'desc');

		if ($offset) {
			$query->offset($offset*15);
		}

		$posts = $query->limit(15)->get();

		foreach ($posts as $post) {
			$post->organisation = Organisation::find($post->organisation_id);
		}

		return $posts;
	}

	protected function feed($id = false) {
		if ($id !== false) {
		    $posts = self::where('organisation_id', $id)->orderBy('created_at', 'desc')->limit(10)->get();

		    foreach ($posts as $post) {
		        $post->organisation = Organisation::find($id);
            }

            return $posts;
		}
		else { return self::getByUser(Auth::user()->id); }
	}

	protected function feedExpand($id, $offset) {
		if ($id !== false) {
		    $posts =  self::where('organisation_id', $id)->latest()->offset($offset*15)->limit(15)->get();
            foreach ($posts as $post) {
                $post->organisation = Organisation::find($id);
            }
            return $posts;
		}
		else { return self::getByUser(Auth::user()->id); }
	}

	protected function canExpand($offset, $org_id = false) {
	    $result = false;
        if ($org_id) {
            $result = self::select('id')->where('organisation_id', $org_id)->get();
        } else {
            $result = self::select('posts.id')->join('organisation_roles', 'organisation_roles.organisation_id', '=', 'posts.organisation_id')
                ->where('organisation_roles.user_id', Auth::user()->id)->orderBy('posts.created_at', 'desc');
        }
	    if (count($result) > ($offset)*10) {
            return '1';
        } else { return '0'; }
    }
}
