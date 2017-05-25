<?php

$api = app('Dingo\Api\Routing\Router');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api->version('v1', ['namespace' => 'App\Http\Controllers\API\v1\Admin'], function ($api) {

    $api->group(['prefix' => '/admin', 'middleware' => 'api.custom.auth.guard:admin'], function ($api) {
        $api->get('/', 'UserController@login');
        $api->post('/login', 'AuthController@authenticate');
        $api->post('/logout', 'AuthController@logout');

        # Authenticated Section
        $api->group(['middleware' => 'api.custom.auth:admin'], function ($api) {
            $api->get('/me', 'UserController@getMe');
            $api->get('/comments', 'UserController@getComments');
        });
    });
});