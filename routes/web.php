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

Route::get('/', 'HomeController@index')->name('home');
Route::get('about', 'HomeController@about')->name('about');
Route::get('search', 'HomeController@search')->name('search');
Route::get('sitemap', 'HomeController@sitemap')->name('sitemap');

// Users
Route::group(['prefix' => 'users'], function () {
	Route::get('search', 'UserController@search')->name('users.search');
	Route::get('search', 'UserController@find')->name('users.find');
	Route::get('{slug}', 'UserController@show')->name('users.show');
});

// Routes requiring login
Route::group(['middleware' => 'auth'], function () {
	Route::get('/dashboard', 'HomeController@home');
	Route::get('/my-teams', 'TeamController@mine');
	Route::get('/my-subscriptions', 'OrganisationController@mySubscriptions');
	Route::get('/my-organisations', 'OrganisationController@mine');
	Route::resource('messages', 'ConversationController', ['only' => ['index', 'create', 'show']]);
    Route::resource('lobbies', 'LobbyController', ['except' => ['index', 'edit', 'update']]);

	// Users
	Route::get('settings', 'UserController@edit')->name('settings');
	Route::match(['put', 'patch'], 'settings', 'UserController@update')->name('users.update');
	Route::get('profile', 'UserController@show')->name('users.profile');
	Route::get('notifications', 'UserController@notifications')->name('users.notifications');

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

	// Orgnaisations
	Route::resource('organisations', 'OrganisationController', ['except' => ['index', 'show']]);
	
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
        Route::get('/{slug}/lfg', 'TeamController@lfg')->name('teams.lfg');
	});
	Route::get('{slug}', 'TeamController@show')->name('teams.show');
	Route::get('{slug}/members', 'TeamController@members')->name('teams.members');
	Route::get('/', 'TeamController@index')->name('teams.index');
});

// Organisations
Route::group(['prefix' => 'organisations'], function() {
	Route::get('/', 'OrganisationController@index')->name('organisations.index');
	Route::get('/{id}', 'OrganisationController@show')->name('organisations.show');
	Route::get('/{id}/add-event', 'EventController@create')->name('events.create');
	Route::post('/{id}/add-event', 'EventController@store')->name('events.store');
});

// Events
Route::group(['prefix' => 'events'], function () {
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/{event_id}/manage', 'EventController@manage')->name('events.manage');
        Route::post('/{event_id}/enter', 'EventController@enter')->name('events.enter');
        Route::match(['get', 'post'], '/{event_id}/round/add', 'RoundController@store')->name('events.add-round');
    });
    Route::get('/{event_id}/schedule', 'EventController@manage')->name('events.schedule');
    Route::get('/', 'EventController@index')->name('events.index');
	Route::get('/{id}', 'EventController@show')->name('events.show');
	Route::get('/{id}/leaderboard', 'EventController@leaderboard')->name('events.leaderboard');
});

// Ajax
Route::group(['middleware' => 'ajax', 'prefix' => 'ajax'], function () {
	Route::group(['middleware' => 'auth'], function () {
		Route::get('notifications/count', 'AjaxController@notificationCount');
		Route::get('notifications/{notification_id}/seen', 'AjaxController@notificationSeen');

		Route::get('friend-requests/count', 'AjaxController@freqCount');

		Route::group(['prefix' => 'conversation'], function() {
		    Route::post('/{conversation_id}/message/send', 'MessageController@send')->name('ajax.message.send');
		    Route::get('/{conversation_id}/get', 'MessageController@getByConversation')->name('ajax.conversation.get');
        });

        Route::group(['prefix' => 'feed'], function() {
            Route::get('/{id?}', 'AjaxController@feed')->name('ajax.feed.get');
            Route::get('/extend/{id?}', 'AjaxController@feedExtend')->name('ajax.feed.extend');
            Route::get('/can-expand/{id?}', 'AjaxController@canExpandFeed')->name('ajax.feed.can-expand');
        });

        Route::get('lfg', 'AjaxController@toggleLfg')->name('ajax.lfg');

		Route::get('organisations/{organisation_id}/sub', 'OrganisationController@subscribe')->name('ajax.sub');
		Route::get('organisations/{organisation_id}/unsub', 'OrganisationController@unsubscribe')->name('ajax.unsub');

        Route::group(['prefix' => 'team'], function() {
            Route::get('/{team_id}/join', 'AjaxController@joinTeam')->name('ajax.team.join');
            Route::get('/{team_id}/leave', 'AjaxController@leaveTeam')->name('ajax.team.leave');

            Route::get('/{team_id}/invite', 'AjaxController@invite')->name('ajax.team.invite');
            Route::post('/{team_id}/invite-batch', 'AjaxController@inviteTeamBatch')->name('ajax.team.invite.batch');

            Route::get('/{team_id}/accept/{user_id}', 'AjaxController@confirmJoin')->name('ajax.team.accept');
            Route::get('/{team_id}/deny/{user_id}', 'AjaxController@denyRequest')->name('ajax.team.deny');

            Route::post('/{team_id}/kick', 'TeamController@kick')->name('ajax.team.kick');

            Route::get('/{team_id}/make-admin/{user_id}', 'TeamController@makeAdmin')->name('ajax.team.admin.make');
            Route::get('/{team_id}/delete-admin/{user_id}', 'TeamController@deleteAdmin')->name('ajax.team.admin.delete');
        });

        Route::get('users/find/{team_id?}', 'UserController@search')->name('ajax.users.search');
        Route::get('users/lfg/get/{team_id}', 'UserController@getLfg')->name('ajax.users.lfg.get');


        Route::post('game/{game_id}/set-winner', 'AjaxController@setGameWinner')->name('ajax.game.winner');
	});
	
	Route::post('organisation/post/{id}', 'OrganisationController@post')->name('ajax.organisations.post');
});

// Admin
Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin'], function () {
    Route::get('/', 'AdminController@index')->name('admin');
    Route::post('organisation/{organisation_id}/trust', 'AdminController@trust')->name('organisation.trust');
    Route::post('organisations/trust', 'AdminController@trust')->name('organisations.trust.batch');

    Route::get('ajax/organisations/find', 'AdminController@getOrganisations');
});