<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $fillable = ['content', 'created_at', 'updated_at', 'organisation_id'];

	protected function make($data, $organisation_id) {
		$post = new Post;
		foreach ($data as $field => $value) {
			$post->{$field} = $value;
		}
		$post->organisation_id = $organisation_id;

		$post->save();

		Notification::send($organisation_id);
	}

	protected function getByUser($user_id) {
		$posts = self::join('organisation_roles', 'organisation_roles.organisation_id', '=', 'organisation.id')
					->where('organisation_roles.user_id', $user_id)-orderBy('created_at', 'desc')->get();

		foreach ($posts as $post) {
			$post->organisation = Organisation::find($post->organisation_id);
		}

		return $posts;
	}
}
