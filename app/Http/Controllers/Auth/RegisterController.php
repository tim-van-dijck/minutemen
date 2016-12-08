<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Validator;

use App\General;
use App\User;

class RegisterController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/home';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'username'	=> 'required|max:255|unique:users',
			'firstname'	=> 'required|max:255',
			'lastname'	=> 'required|max:255',
			'email'		=> 'required|email|max:255|unique:users',
			'password'	=> 'required|min:6|confirmed',
			'street'	=> 'required',
			'number'	=> 'required',
			'zip'		=> 'required',
			'city'		=> 'required',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	protected function create(array $data)
	{
		if (isset($data['img'])) { $img = General::uploadImg($data['img'], 'users', true); }
		else { $img = null; }
		
		return User::create([
			'username'	=> $data['username'],
			'slug'		=> General::sluggify($data['username'], 'users'),
			'firstname'	=> $data['firstname'],
			'lastname'	=> $data['lastname'],
			'email'		=> $data['email'],
			'street'	=> $data['street'],
			'number'	=> $data['number'],
			'zip'		=> $data['zip'],
			'city'		=> $data['city'],
			'coords'	=> $data['coords'],
			'password'	=> bcrypt($data['password']),
			'img'		=> $img,
		]);
	}
}
