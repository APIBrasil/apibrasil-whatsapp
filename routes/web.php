<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers'], function()
{

    Route::get('/', 'FrontEnd\HomeController@index')->name('home.index');

    Route::group(['middleware' => ['guest']], function() {

        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');

        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');

    });

    Route::group(['prefix' => 'painel', 'middleware' => ['auth', 'checkStatus']], function() {

        Route::get('/', 'DashboardController@index')->name('painel.index');
        Route::get('/{limit}/limit', 'DashboardController@index')->name('painel.limit.index');
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
        Route::get('/dashboard/{limit}/limit', 'DashboardController@index')->name('dashboard.limit.index');

        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');

        Route::group(['prefix' => 'users'], function() {
            Route::get('datatables', 'UsersController@datatables')->name('users.datatables');
            Route::get('/', 'UsersController@index')->name('users.index');
            Route::get('/create', 'UsersController@create')->name('users.create');
            Route::post('/create', 'UsersController@store')->name('users.store');
            Route::get('/{user}/show', 'UsersController@show')->name('users.show');
            Route::get('/{user}/edit', 'UsersController@edit')->name('users.edit');
            Route::patch('/{user}/update', 'UsersController@update')->name('users.update');
            Route::delete('/{user}/delete', 'UsersController@destroy')->name('users.destroy');
        });

        Route::group(['prefix' => 'sessions'], function() {
            Route::get('/', 'SessionsController@index')->name('sessions.index');
            Route::post('start', 'SessionsController@start')->name('sessions.start');
            Route::post('/online/{id}/show', 'SessionsController@onlineShow')->name('sessions.online.show');

            Route::delete('{id}/stop', 'SessionsController@stop')->name('sessions.stop');
            Route::get('datatables', 'SessionsController@datatables')->name('sessions.datatables');
            Route::get('create', 'SessionsController@create')->name('sessions.create');
            Route::post('create', 'SessionsController@store')->name('sessions.store');
            Route::get('{post}/show', 'SessionsController@show')->name('sessions.show');
            Route::get('{post}/edit', 'SessionsController@edit')->name('sessions.edit');
            Route::patch('{post}/update', 'SessionsController@update')->name('sessions.update');
            Route::delete('{post}/delete', 'SessionsController@destroy')->name('sessions.destroy');
        });

        Route::group(['prefix' => 'groups'], function() {

            Route::get('buy', 'GroupsController@buy')->name('groups.buy');

            Route::get('images/{id}/get', 'GroupsController@getImage')->name('groups.getImage');

            Route::get('/', 'GroupsController@index')->name('groups.index');
            Route::post('store', 'GroupsController@store')->name('groups.store');
            Route::get('{post}/edit', 'GroupsController@edit')->name('groups.edit');
            Route::patch('/{post}/update', 'GroupsController@update')->name('groups.update');

            Route::get('datatables', 'GroupsController@datatables')->name('groups.datatables');

            Route::post('get', 'GroupsController@getAllGroups')->name('groups.get');
            Route::get('send', 'GroupsController@send')->name('groups.send');
            Route::get('history', 'GroupsController@history')->name('groups.history');

            Route::delete('/{id}/delete', 'GroupsController@destroy')->name('groups.destroy');
            Route::delete('/deleteAll', 'GroupsController@deleteAll')->name('groups.deleteAll');

            Route::post('/{id}/show', 'GroupsController@show')->name('groups.show');
            Route::post('/online/{id}/show', 'GroupsController@onlineShow')->name('groups.online.show');

        });

        Route::group(['prefix' => 'message'], function() {
            Route::get('/', 'MessagesController@index')->name('message.index');
            Route::get('datatables', 'MessagesController@datatables')->name('message.datatables');
            Route::get('/{id}/show', 'MessagesController@show')->name('message.show');
            Route::post('sendText', 'MessagesController@sendText')->name('message.sendText');
            Route::delete('/{id}/delete', 'MessagesController@destroy')->name('message.destroy');
            Route::get('chart-js',  'MessagesController@chart')->name('message.chart');
        });

        Route::group(['prefix' => 'contacts'], function() {

            Route::get('export', 'ContactsController@export')->name('contacts.export');
            Route::post('import', 'ContactsController@import')->name('contacts.import');

            Route::get('/', 'ContactsController@index')->name('contacts.index');
            Route::post('create', 'ContactsController@store')->name('contacts.store');
            Route::get('{post}/edit', 'ContactsController@edit')->name('contacts.edit');

            Route::get('datatables', 'ContactsController@datatables')->name('contacts.datatables');
            Route::get('datatables/imported', 'ContactsController@datatablesImported')->name('contacts.imported');

            Route::patch('/{post}/update', 'ContactsController@update')->name('contacts.update');
            Route::post('/{id}/show', 'ContactsController@show')->name('contacts.show');

            Route::post('extracts', 'ContactsController@extracts')->name('contacts.extracts');

            Route::get('send', 'ContactsController@index')->name('contacts.send');
            Route::get('history', 'ContactsController@index')->name('contacts.history');

            Route::delete('{id}/delete', 'ContactsController@destroy')->name('contacts.destroy');
            Route::delete('destroy/{situacao}', 'ContactsController@destroyAll')->name('contacts.destroyAll');

        });

        Route::group(['prefix' => 'templates'], function() {
            Route::get('messages', 'TemplatesMensages@index')->name('templates.index');
            Route::get('messages/create', 'TemplatesMensages@create')->name('templates.create');
            Route::post('messages/create', 'TemplatesMensages@store')->name('templates.store');
            Route::get('messages/{id}/show', 'TemplatesMensages@show')->name('templates.show');
            Route::get('messages/{id}/edit', 'TemplatesMensages@edit')->name('templates.edit');
            Route::patch('messages/{id}/update', 'TemplatesMensages@update')->name('templates.update');
            Route::delete('messages/{id}/delete', 'TemplatesMensages@destroy')->name('templates.destroy');
        });

        Route::group(['prefix' => 'tags'], function() {
            Route::get('/', 'TagsController@index')->name('tags.index');
            Route::get('/create', 'TagsController@create')->name('tags.create');
            Route::post('/create', 'TagsController@store')->name('tags.store');
            Route::get('/{post}/show', 'TagsController@show')->name('tags.show');
            Route::get('/{post}/edit', 'TagsController@edit')->name('tags.edit');
            Route::patch('/{post}/update', 'TagsController@update')->name('tags.update');
            Route::delete('/{post}/delete', 'TagsController@destroy')->name('tags.destroy');
        });

        Route::group(['prefix' => 'posts'], function() {
            Route::get('/', 'PostsController@index')->name('posts.index');
            Route::get('/create', 'PostsController@create')->name('posts.create');
            Route::post('/create', 'PostsController@store')->name('posts.store');
            Route::get('/{post}/show', 'PostsController@show')->name('posts.show');
            Route::get('/{post}/edit', 'PostsController@edit')->name('posts.edit');
            Route::patch('/{post}/update', 'PostsController@update')->name('posts.update');
            Route::delete('/{post}/delete', 'PostsController@destroy')->name('posts.destroy');
        });

        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);
    });
});
