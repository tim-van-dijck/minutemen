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
	Route::resource('events', 'EventController', ['only' => ['edit', 'update', 'delete']]);
});

// Leaderboard
Route::group(['prefix' => 'leaderboard'], function() {
	Route::get('/', 'LeaderboardController@teams')->name('leaderboards.teams');
	Route::get('/players', 'LeaderboardController@players')->name('leaderboards.players');
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
	Route::get('/{id}/add-event', 'EventController@create')->name('events.create');
	Route::post('/{id}/add-event', 'EventController@store')->name('events.store');
});

// Events
Route::group(['prefix' => 'events'], function () {
	Route::get('/', 'EventController@index')->name('events.index');
	Route::get('/{id}', 'EventController@show')->name('events.show');
	Route::post('/{event_id}/enter', 'EventController@enter')->name('events.enter');
});

// Ajax
Route::group(['middleware' => 'ajax', 'prefix' => 'ajax'], function () {
	Route::group(['middleware' => 'auth'], function () {
		Route::get('notifications/count', 'AjaxController@notificationCount');
		Route::get('feed/{id?}', 'AjaxController@feed')->name('ajax.feed.get');

		Route::get('lfg', 'UserController@toggleLfg')->name('ajax.lfg');

		Route::get('ajax/sub', 'AjaxController@subscribe')->name('ajax.sub');
		Route::get('ajax/unsub', 'AjaxController@unsubscribe')->name('ajax.unsub');

		Route::get('ajax/team/{team_id}/join', 'AjaxController@joinTeam')->name('ajax.team.join');
		Route::get('ajax/team/{team_id}/leave', 'AjaxController@leaveTeam')->name('ajax.team.leave');

		Route::get('ajax/team/{team_id}/accept/{user_id}', 'AjaxController@confirmJoin')->name('ajax.team.accept');
		Route::get('ajax/team/{team_id}/deny/{user_id}', 'AjaxController@denyRequest')->name('ajax.team.deny');
	});
	
	Route::post('organisation/post/{id}', 'OrganisationController@post')->name('ajax.organisations.post');
});
