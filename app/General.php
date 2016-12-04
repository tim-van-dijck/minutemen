<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;
use DB;

class General extends Model
{
    protected function search($query) {
		$users = User::select('*')->where('username', 'LIKE', '%'.$query.'%')->orWhere('firstname', 'LIKE', '%'.$query.'%')->orWhere('lastname', 'LIKE', '%'.$query.'%')->orderBy('username')->get();

		$teams = Team::select('*')->where('name', 'LIKE', '%'.$query.'%')->orWhere('tag', 'LIKE', '%'.$query.'%')->orWhere('description', 'LIKE', '%'.$query.'%')->orderBy('name')->get();

		$events = Event::select('*')->where('title', 'LIKE', '%'.$query.'%')->orWhere('description', 'LIKE', '%'.$query.'%')->orderBy('title')->get();
        
		$organisations = Organisation::select('*')->where('name', 'LIKE', '%'.$query.'%')->orWhere('description', 'LIKE', '%'.$query.'%')->orderBy('name')->get();

    	$results = [
    		'users'			=> $users,
    		'teams'			=> $teams,
    		'events'		=> $events,
    		'organisations'	=> $organisations,
    	];

    	return $results;
    }

    protected function uploadImg($file, $path, $thumb = false) {
        $exists = true;
        $hash = '';
        $size = 1024;

        if ($thumb) { $size = 250; }

        while ($exists) {
            $hash = 'img/'.$path.'/'.hash('sha512', str_random(40));

            if (is_string($file)) { $hash.='.png'; }
            else { $hash.='.'.$file->getClientOriginalExtension(); }

            $exists = file_exists(public_path($hash));
        }

        if (!is_string($file)) { $file = $file->getRealPath(); }

        $img = Image::make($file)
                ->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save(public_path($hash));

        return $hash;
    }

    protected function sluggify($string, $table) {
        $slug = preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $string);
        $col = DB::table($table)->where('slug', $slug)->get();

        if (!$col->isEmpty()) { $slug .= count($col); }
        
        return $slug;
    }
}
