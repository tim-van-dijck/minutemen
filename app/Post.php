<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Post extends Model
{
	protected $fillable = ['content', 'created_at', 'updated_at', 'organisation_id'];

	protected function make($data) {
		$data['content'] = nl2br($data['content']);
		
		$post = new Post($data);
		$post->save();

		// Notification::send($organisation_id);
	}

	protected function getByUser($user_id, $offset = false) {
		$query = self::join('organisation_roles', 'organisation_roles.organisation_id', '=', 'posts.organisation_id')
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

	protected function feed($id) {
		if ($id !== false) { return self::where('organisation_id', $id)->latest()->limit(15)->get(); }
		else { return self::getByUser(Auth::user()->id); }
	}

	protected function feedExpand($id, $offset) {
		if ($id !== false) { return self::where('organisation_id', $id)->latest()->offset($offset*15)->limit(15)->get(); }
		else { return self::getByUser(Auth::user()->id); }
	}
}
