# [Laravel+VueJS] Single Page Application 實作篇 (Laravel篇)

## Introduction
Let’s to use Laravel to do SPA (Single Page Application) with VueJS.

## Source Code
### Beackend
- [Maras0830/spa-example-with-laravel-vue-backend](https://github.com/Maras0830/spa-example-with-laravel-vue-backend)
### Frontend
- [Maras0830/spa-example-with-laravel-vue-frontend](https://github.com/Maras0830/spa-example-with-laravel-vue-frontend)
- [Maras0830/spa-example-with-laravel-vue-frontend-nuxtjs](https://github.com/Maras0830/spa-example-with-laravel-vue-frontend-nuxtjs)
### API Document
- [Maras0830/spa-example-with-laravel-vue-doc](https://github.com/Maras0830/spa-example-with-laravel-vue-doc)

## Back-end Framwork 
- [Laravel 5.4](https://github.com/laravel/laravel)

## Packages
- [Dingo 1.0.*@dev](https://github.com/dingo/api)
- [jwt-auth@0.5.*](https://github.com/tymondesigns/jwt-auth)


### Dingo
*Features*
- Content Negotiation
- Multiple Authentication Adapters => 多重身份認證
- API Versioning => API 版本控制
- Rate Limiting => 限制每小時處理需求的限制
- Response Transformers and Formatters => 正規化處理
- Error and Exception Handling => 意外處理
- Internal Requests
- API Blueprint Documentation

## Laravel
### Step1. Require packages && composer update
*composer.json*
```
"require": {
    // add dingo package.
    "dingo/api": "1.0.*@dev"
},
```
or
```
composer require dingo/api:1.0.x@dev
```

### Step2. Add provider to app.php and setting dingo config
*config\app.php*
```
$providers => [
// add dingo provider.
Dingo\Api\Provider\LaravelServiceProvider::class
];
```

*published dingo provider*
```
$ php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"
```

### Step3. Environment
*.env*
```
DB_DATABASE=laravelConf2017
DB_USERNAME=homestead
DB_PASSWORD=secret

API_STANDARDS_TREE=vnd
API_PREFIX=api
API_DOMAIN=yourdomain.com
API_NAME=LaravelConf
API_DEFAULT_FORMAT=json
```

### Step4. Model
*Add Model*
```
$ php artisan make:model Admin -m
$ php artisan make:model Post -m
$ php artisan make:model Comment -m
```

*Model*

*Admin migration*
```
Schema::create('admins', function (Blueprint $table) {
   $table->increments('id');
   $table->string('name');
   $table->string('email')->unique();
   $table->string('password');
   $table->rememberToken();
   $table->timestamps();
   $table->softDeletes();
});
```

*Post migration*
```
Schema::create('posts', function (Blueprint $table) {
    $table->increments('id');
    $table->string('title', 100);
    $table->text('content');
    $table->integer('author_id');
    $table->timestamps();
    $table->softDeletes();
});
```

*Comment migration*
```
Schema::create('comments', function (Blueprint $table) {
    $table->increments('id');
    $table->string('title');
    $table->text('content');
    $table->integer('post_id');
    $table->integer('comment_from_id');
    $table->string('comment_from_type');
    $table->integer('comment_id');
    $table->timestamps();
    $table->softDeletes();
});
```

*Migrate*
```
php artisan migrate
```

*Setting Model Relation*
*App\User.php*
```
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function comment_from()
    {
        return $this->morphMany('App\Comment', 'comment_from');
    }
}
```
*App\Admin.php*
```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Posts()
    {
        return $this->hasMany('App\Post');
    }

    public function Comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function comment_from()
    {
        return $this->morphMany('App\Comment', 'comment_from');
    }
}
```
*App\Comment.php*
```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'comment_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'comment_from_id', 'comment_from_type', 'comment_id'
    ];

    public function post()
    {
        return $this->belongsTo('App\Post');
    }

    public function main_comment()
    {
        return $this->belongsTo('App\Comment');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function comment_from()
    {
        return $this->morphTo();
    }
}
```
*App\Post.php*
```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'author_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'author_id'
    ];

    public function author()
    {
        return $this->belongsTo('App\Admin');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
```

*Seeder*
```
php artisan make:seeder AdminSeeder
php artisan make:seeder UserSeeder
```
*database\seeds\AdminSeeder.php*
```
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name' => str_random(10),
            'email' => 'maraschen@gmail.com',
            'password' => bcrypt('secret')
        ]);
    }
}
```
*database\seeds\UserSeeder.php*
```
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i<3; $i++)
            DB::table('users')->insert([
                'name' => str_random(10),
                'email' => str_random(10).'@gmail.com',
                'password' => bcrypt('secret'),
            ]);
    }
}
```
*Execute Seeder*
```
php artisan db:seed
```

### Step5. Authentication [Option]
*Using JSON Web Tokens(JWT)*
Require packages && composer update

*composer.json*
```
"require": {
    // add jwt-auth package.
    "tymon/jwt-auth": "^0.5.11"
},
```
or
```
composer require tymon/jwt-auth
```

*config\api.php*
```
'auth' => [
    // using jwt to auth.
    'jwt' => 'Dingo\Api\Auth\Provider\JWT',
],
```

*config\app.php*
```
'providers' => [
    // Add JWT-auth providers
    Dingo\Api\Provider\LaravelServiceProvider::class,
    Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class
],

'aliases' => [
	  // Add JWT-auth alias
    'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
    'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class
]
```

*publish JWT-auth provider*
```
$ php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\JWTAuthServiceProvider"
```

*Don't forget to set a secret key in the config file!*
```
$ php artisan jwt:generate
```

*Multiple auth route*
*app\Providers\RouteServiceProvider.php*
```
public function map() 
{
    // Add Admin API route.
    $this->mapAdminApiRoutes();
}

protected function mapAdminApiRoutes()
{
    Route::prefix('api/admin')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/api-admin.php'));
}
```

*Add Auth Controller*
```
$ php artisan make:controller API/v1/User/AuthController
$ php artisan make:controller API/v1/Admin/AuthController
```

*config\auth.php*
```
'guards' => [
    'api' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],
],
```

*Add Guard Middleware*
```
$ php artisan make:middleware GuardAuthenticationMiddleware
```

*app\Http\Middleware\GuardAuthenticationMiddleware.php*
```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class GuardAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $auth
     * @return mixed
     */
    public function handle($request, Closure $next, string $auth = '')
    {
        if (!empty($auth))
            Config::set('auth.defaults.guard', $auth);

        return $next($request);
    }
}
```

*Authenticate*
*app\Http\Controllers\API\v1\User\AuthController.php*
```
<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
}
```

*app\Http\Controllers\API\v1\Admin\AuthController.php*
```
<?php

namespace App\Http\Controllers\API\v1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
}
```

*Add Auth Middleware*
```
$ php artisan make:middleware AuthenticationMiddleware
```

*app\Http\Middleware\AuthenticationMiddleware.php*
```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $auth
     * @return mixed
     */
    public function handle($request, Closure $next, string $auth = '')
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

            $request->merge(['auth' => $user]);

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return $next($request);
    }
}
```
*Add UserController*
```
$ php artisan make:controller API/v1/User/UserController 
$ php artisan make:controller API/v1/Admin/UserController 
```

*app\Http\Controllers\API\v1\User\UserController.php*
```
<?php

namespace App\Http\Controllers\API\v1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
}
```

*app\Http\Controllers\API\v1\Admin\UserController.php*

```
<?php

namespace App\Http\Controllers\API\v1\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
}
```

*route middleware*

*Http\Middleware\ GuardAuthenticationMiddleware.php*
```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class GuardAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $auth
     * @return mixed
     */
    public function handle($request, Closure $next, string $auth = '')
    {
        if (!empty($auth))
            Config::set('auth.defaults.guard', $auth);

        return $next($request);
    }
}
```

*app\Http\Kernel.php*
```
protected $routeMiddleware = [
    // add Auth middlewares.
    'api.custom.auth' =>  \App\Http\Middleware\AuthenticationMiddleware::class,
    'api.custom.auth.guard' =>  \App\Http\Middleware\GuardAuthenticationMiddleware::class
];
```

*routes\api.php*
```
<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'api.custom.auth.guard:api', 'namespace' => 'App\Http\Controllers\API\v1\User'], function ($api) {

    $api->post('/login', 'AuthController@authenticate');

    # Authenticated Section
    $api->group(['middleware' => 'api.custom.auth:api'], function ($api) {
        $api->get('/me', 'UserController@getMe');
    });
});
```
*routes\api-route.php*
```
<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['middleware' => 'api.custom.auth.guard:admin', 'namespace' => 'App\Http\Controllers\API\v1\User'], function ($api) {

    $api->post('/login', 'AuthController@authenticate');

    # Authenticated Section
    $api->group(['middleware' => 'api.custom.auth:admin'], function ($api) {
        $api->get('/me', 'UserController@getMe');
    });
});
```

And you can get token when your call login api endpoints.
```
$ curl -X POST \
  'http://yourdomain.dev/api/login?email=EsUTA546Qf%40gmail.com&password=secret' \
  -H 'cache-control: no-cache' \
  -H 'postman-token: 70cc7f71-3c83-464b-02db-b32f8c4238af'
```

Response:
```
{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL2xhcmF2ZWxjb25mLmRldlwvYXBpXC9sb2dpbiIsImlhdCI6MTQ5NTQ1MTg0OCwiZXhwIjoxNDk1NDU1NDQ4LCJuYmYiOjE0OTU0NTE4NDgsImp0aSI6Ik1XN2pONmg0VkFsYU5STE8ifQ.zkEtrV0Fxa6SVw-2hgWsKiKeYQntaIa23MggxHmx2pI"}
```

And you can using response token to get user data, like this:
```
$ curl -X GET \
  http://yourdomain.dev/api/me \
  -H 'authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjMsImlzcyI6Imh0dHA6XC9cL2xhcmF2ZWxjb25mLmRldlwvYXBpXC9sb2dpbiIsImlhdCI6MTQ5NTQ1MTg0OCwiZXhwIjoxNDk1NDU1NDQ4LCJuYmYiOjE0OTU0NTE4NDgsImp0aSI6Ik1XN2pONmg0VkFsYU5STE8ifQ.zkEtrV0Fxa6SVw-2hgWsKiKeYQntaIa23MggxHmx2pI' \
  -H 'cache-control: no-cache' \
  -H 'postman-token: ec7745b9-8e22-9771-9547-d20150c29770'
```

Response:
```
{"data":{"id":3,"name":"MG7wJ66swT","email":"EsUTA546Qf@gmail.com","created_at":null,"updated_at":null,"deleted_at":null},"status_code":200,"message":"successful."}
```

### Step5. Transformer


### Step6. Controller 
```
$ php artisan make:controller API/v1/UserController
$ php artisan make:controller API/v1/AdminController
$ php artisan make:controller API/v1/PostController
$ php artisan make:controller API/v1/CommentController
```

### Step6. Transformer
Transformers allow you to easily and consistently transform objects into an array

*app\Http\Controllers\API\v1\PostController.php*
```
<?php

namespace App\Http\Controllers\API\v1;

use App\Post;
use App\Transformers\PostTransformer;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    use Helpers;

    public function getPosts()
    {
        $posts = Post::paginate(10);

        return $this->response->paginator($posts, new PostTransformer());
    }
}
```

*create Transformer*
*app\Transformers\PostTransformer.php*
```
<?php
namespace App\Transformers;

use App\Post;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @param Post $post
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'id'           => (int) $post->id,
            'title'        => $post->title,
            'content'      => $post->content,
            'created_at'   => $post->created_at
        ];
    }

}
```


## Front-end Framework (Not Finish)
[Vue.js@v2.3.3](https://vuejs.org/)

## Packages
[Vuex@2.x](https://vuex.vujs.org/)
[Vue-route@2.x](https://router.vuejs.org/en/)
[Vue-resource@1.3.1](https://github.com/pagekit/vue-resource/)
[Vue-head](https://github.com/ktquez/vue-head)
[axios](https://github.com/mzabriskie/axios)
