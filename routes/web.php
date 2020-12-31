<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//$router->get('/', function () use ($router) {
//    return $router->app->version();
//});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signUp');
	Route::post('refresh', 'AuthController@refresh');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

$router->group([
    'prefix' => 'api/v1',
    'middleware' => 'auth:api'
], function () use ($router){
    $router->get('/livros', 'LivrosController@index');
    $router->get('/livros/{id}', 'LivrosController@show');
    $router->post('/livros', 'LivrosController@store');
    $router->patch('/livros/{id}', 'LivrosController@update');
    $router->delete('/livros/{id}', 'LivrosController@destroy');
	$router->post('/livros/contato', 'LivrosController@contact');
    $router->post('/livros/upload/{id}', 'LivrosController@upload');
    $router->get('/livros/exportar/{formato}', 'LivrosController@export');
});

$router->get('/api/v1/livros/doc', 'LivrosController@doc');
