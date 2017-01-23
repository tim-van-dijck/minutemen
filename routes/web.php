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
    // My stuff
	Route::get('/dashboard', 'HomeController@home')->name('dashboard');
	Route::get('/home', 'HomeController@home')->name('dashboard');
	Route::get('/my-teams', function() { return redirect()->route('dashboard'); });

	Route::get('/my-subscriptions', 'OrganisationController@mySubscriptions')->name('my-subscriptions');
	Route::get('/my-organisations', 'OrganisationController@mine')->name('my-organisations');

	// Messages
	Route::resource('conversations', 'ConversationController', ['only' => ['index', 'create', 'show', 'destroy']]);
	Route::delete('conversations/{id}/leave', 'ConversationController@leaveConversation')->name('conversations.leave');
	Route::get('conversations/new/{user_id}', 'ConversationController@create')->name('conversations.with');

	// Lobbies
    Route::resource('lobbies', 'LobbyController', ['except' => ['index', 'edit', 'update']]);
    Route::delete('lobbies/{id}/leave', 'LobbyController@leave')->name('lobbies.leave');
    Route::get('/{lobby_id}/accept-invite/{notification_id}', 'LobbyController@acceptInvite')->name('lobby.accept-invite');
    Route::get('/{lobby_id}/deny-invite/{notification_id}', 'LobbyController@denyInvite')->name('lobby.deny-invite');

	// Users
	Route::get('settings', 'UserController@settings')->name('settings');
	Route::put('settings', 'UserController@updateSettings')->name('settings.update');
	Route::get('edit-profile', 'UserController@edit')->name('users.edit');
	Route::delete('delete-account', 'UserController@destroy')->name('users.destroy');
	Route::match(['put', 'patch'], 'settings', 'UserController@update')->name('users.update');
	Route::get('profile', 'UserController@show')->name('users.profile');
	Route::get('notifications', 'UserController@notifications')->name('users.notifications');

	// Friends
	Route::get('users', function() { return redirect('friends'); });
    Route::get('users/{slug}/friends', 'UserController@friends')->name('users.friends');
	Route::group(['prefix' => 'friends'], function () {
		Route::get('/', 'UserController@friends')->name('users.friends');
		Route::post('{slug}/add', 'UserController@addFriend');
		Route::get('{friendship_id}/confirm', 'UserController@confirmFriend');
		Route::delete('{friendship_id}/delete', 'UserController@deleteFriend')->name('friendship.delete');
	});

	// Teams
	Route::resource('teams', 'TeamController', ['except' => ['edit', 'show', 'index']]);
    Route::delete('teams/{team_id}/leave', 'TeamController@leave')->name('team.leave');

	// Orgnaisations
	Route::resource('organisations', 'OrganisationController', ['except' => ['index', 'show']]);
	
	// Events
	Route::resource('events', 'EventController', ['only' => ['edit', 'update', 'destroy']]);
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
	    // ajax/notifications
		Route::get('notifications/count', 'AjaxController@notificationCount');
		Route::get('notifications/{notification_id}/seen', 'AjaxController@notificationSeen');

		// ajax/friend-requests
		Route::get('friend-requests/count', 'AjaxController@freqCount');

		// ajax/conversation
		Route::group(['prefix' => 'conversation'], function() {
		    Route::post('/{conversation_id}/message/send', 'MessageController@send')->name('ajax.message.send');
            Route::post('/{conversation_id}/add-recipients', 'ConversationController@addRecipients')->name('ajax.conversation.add-recipients');
            Route::post('/{conversation_id}/set-title', 'ConversationController@setTitle')->name('ajax.conversation.set-title');
		    Route::get('/{conversation_id}/get', 'MessageController@getByConversation')->name('ajax.conversation.get');
		    Route::delete('/{conversation_id}/destroy-if-empty', 'ConversationController@destroyIfEmpty')->name('ajax.conversation.destroy-empty');
        });

		// ajax/feed/
        Route::group(['prefix' => 'feed'], function() {
            Route::get('/{id?}', 'AjaxController@feed')->name('ajax.feed.get');
            Route::get('/extend/{id?}', 'AjaxController@feedExtend')->name('ajax.feed.extend');
            Route::get('/can-expand/{id?}', 'AjaxController@canExpandFeed')->name('ajax.feed.can-expand');
        });

        // ajax/lfg
        Route::get('lfg', 'AjaxController@toggleLfg')->name('ajax.lfg');

        // ajax/lobby
        Route::group(['prefix' => 'lobby'], function () {
            Route::get('/{lobby_id}/player-count', 'AjaxController@getLobbyPlayerCount')->name('ajax.lobby.get-player-count');
            Route::get('/{lobby_id}/get-players', 'AjaxController@getLobbyPlayers')->name('ajax.lobby.get-players');
            Route::post('/{lobby_id}/invite', 'LobbyController@invite')->name('ajax.lobby.invite');
        });

        // ajax/orgnaisations
		Route::get('organisations/{organisation_id}/sub', 'OrganisationController@subscribe')->name('ajax.sub');
		Route::get('organisations/{organisation_id}/unsub', 'OrganisationController@unsubscribe')->name('ajax.unsub');

		// ajax/team
        Route::group(['prefix' => 'team'], function() {
            Route::get('/{team_id}/join', 'AjaxController@joinTeam')->name('ajax.team.join');

            Route::get('/{team_id}/invite', 'AjaxController@invite')->name('ajax.team.invite');
            Route::post('/{team_id}/invite-batch', 'AjaxController@inviteTeamBatch')->name('ajax.team.invite.batch');

            Route::get('/{team_id}/accept/{user_id}', 'AjaxController@confirmJoin')->name('ajax.team.accept');
            Route::get('/{team_id}/deny/{user_id}', 'AjaxController@denyRequest')->name('ajax.team.deny');

            Route::post('/{team_id}/kick', 'TeamController@kick')->name('ajax.team.kick');

            Route::get('/{team_id}/make-admin/{user_id}', 'TeamController@makeAdmin')->name('ajax.team.admin.make');
            Route::get('/{team_id}/delete-admin/{user_id}', 'TeamController@deleteAdmin')->name('ajax.team.admin.delete');
        });

        // ajax/users
        Route::get('users/find/{team_id?}', 'UserController@search')->name('ajax.users.search');
        Route::get('users/lfg/get/{team_id}', 'UserController@getLfg')->name('ajax.users.lfg.get');
        Route::get('me/find-acquaintances', 'UserController@findAcquaintances')->name('ajax.user.acquaintances');
        Route::get('me/find-recipients/{conversation_id}', 'UserController@findRecipients')->name('ajax.user.recipients');
        Route::get('lfg/find-lobby', 'AjaxController@findLobby')->name('ajax.lobby.find');
        Route::post('me/hide-tutorial', 'UserController@hideTutorial')->name('ajax.tutorial.hide');

        // ajax/game
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