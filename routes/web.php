<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('search', 'HomeController@search');

// Users
Route::group(['prefix' => 'users'], function () {
	Route::get('search', 'UserController@search')->name('users.search');
	Route::get('search', 'UserController@find')->name('users.find');
	Route::get('{slug}', 'UserController@show')->name('users.show');
});

// Routes requiring login
Route::group(['middleware' => 'auth'], function () {
	Route::get('/home', 'HomeController@home');

	// Users
	Route::get('settings', 'UserController@edit')->name('users.edit');
	Route::match(['put', 'patch'], 'settings', 'UserController@update')->name('users.update');
	Route::get('profile', 'UserController@show')->name('users.profile');

	// Friends
	Route::get('users', function() { return redirect('friends'); });
	Route::group(['prefix' => 'friends'], function () {
		Route::get('/', 'UserController@friends')->name('users.friends');
		Route::get('{slug}/add', 'UserController@addFriend');
		Route::get('{friendship_id}/confirm', 'UserController@confirmFriend');
		Route::get('{friendship_id}/delete', 'UserController@deleteFriend');
	});

	// Teams
	Route::resource('teams', 'TeamController', ['except' => ['edit', 'show', 'index']]);

	// Teams
	Route::resource('organisations', 'OrganisationController', ['except' => ['index']]);
	
	// Events
	Route::resource('events', 'EventController', ['except' => ['index', 'show']]);
});

// Teams
Route::group(['prefix' => 'teams'], function() {
	Route::group(['middleware' => 'auth'], function() {
		Route::get('{slug}/edit', 'TeamController@edit')->name('teams.edit');
	});
	Route::get('{slug}', 'TeamController@show')->name('teams.show');
	Route::get('/', 'TeamController@index')->name('teams.index');
});

// Organisations
Route::group(['prefix' => 'organisations'], function() {
	Route::get('/', 'OrganisationController@index')->name('organisations.index');
});

// Events
Route::group(['prefix' => 'events'], function () {
	Route::get('/', 'EventController@index')->name('events.index');
	Route::get('/{id}/show', 'EventController@show')->name('events.show');
});

// Ajax
Route::group(['middleware' => 'ajax', 'prefix' => 'ajax'], function () {
	Route::get('notifications/count', 'AjaxController@notificationCount');
	Route::get('feed/{id?}', 'AjaxController@feed')->name('ajax.feed.get');
	Route::post('organisation/post/{id}', 'OrganisationController@post')->name('ajax.organisations.post');
});
