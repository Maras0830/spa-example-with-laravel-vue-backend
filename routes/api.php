<?php

app('Dingo\Api\Transformer\Factory')->register('Post', 'PostTransformer');
app('Dingo\Api\Transformer\Factory')->register('Admin', 'AdminTransformer');

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

$api->version('v1', ['prefix' => 'api', 'namespace' => 'App\Http\Controllers\API\v1\User'], function ($api) {

//    # GET POSTS LIST
    $api->get('/posts', 'PostController@getPosts')->name('posts.index');
//    # GET POST
    $api->get('/posts/{id}', 'PostController@getPost')->name('posts.show');


    $api->group(['middleware' => 'api.custom.auth.guard:user'], function ($api) {

        $api->post('/login', 'AuthController@authenticate')->name('user.login');
        $api->post('/logout', 'AuthController@logout');

        # Authenticated Section
        $api->group(['middleware' => 'api.custom.auth:user'], function ($api) {
            $api->get('/me', 'UserController@getMe')->name('user.me');
            $api->get('/comments', 'UserController@getComments')->name('user.comments.index');
        });
    });
});