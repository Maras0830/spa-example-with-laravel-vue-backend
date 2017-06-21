<?php

namespace Tests\Feature\Controller\users;

use App\Admin;
use App\Comment;
use App\Post;
use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;

class CommentControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     * @group users.controller.comments
     * @test
     * @return void
     */
    public function testPostSubComment()
    {
        $email = 'maraschen@codingweb.tw';
        $title = 'reply Hello LaravelConf';
        $content = 'reply Hello LaravelConf 2017.';
        $user = factory(User::class)->create([
            'email' => $email
        ]);

        $token = JWTAuth::fromUser($user, ['type' => 'user']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = ['title' => $title, 'content' => $content];
        // Arrange
        factory(Post::class, 3)->create()
            ->each(function($post) use ($user){
                factory(Comment::class, 8)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => $user->id,
                    'comment_from_type' => 'App\\User',
                ])->each(function ($comment) use ($post, $user){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => $user->id,
                        'comment_from_type' => 'App\\User',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('POST', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('user.comments.store.sub', [1, 1]), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'content', 'time_ago', 'author' => [], 'sub_comments' => []],
        ]);
        $response->assertJson(['data' => ['title' => $title, 'content' => $content, 'author' => ['data' => ['email' => $email]]]]);
    }
    
    /**
     *
     * @group users.controller.comments
     * @test
     * @return void
     */
    public function testPostComment()
    {
        $email = 'maraschen@codingweb.tw';
        $title = 'reply Hello LaravelConf';
        $content = 'reply Hello LaravelConf 2017.';
        $user = factory(User::class)->create([
            'email' => $email
        ]);

        $token = JWTAuth::fromUser($user, ['type' => 'user']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = ['title' => $title, 'content' => $content];
        // Arrange
        factory(Post::class, 3)->create()
            ->each(function($post) use ($user){
                factory(Comment::class, 8)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => $user->id,
                    'comment_from_type' => 'App\\User',
                ])->each(function ($comment) use ($post, $user){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => $user->id,
                        'comment_from_type' => 'App\\User',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('POST', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('user.comments.store', 1), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'content', 'time_ago', 'author' => [], 'sub_comments' => []],
        ]);
        $response->assertJson(['data' => ['title' => $title, 'content' => $content, 'author' => ['data' => ['email' => $email]]]]);
    }

    /**
     *
     * @group users.controller.comments
     * @test
     * @return void
     */
    public function testPutComment()
    {
        $email = 'maraschen@codingweb.tw';
        $title = 'reply Hello LaravelConf';
        $content = 'reply Hello LaravelConf 2017.';
        $user = factory(User::class)->create([
            'email' => $email
        ]);

        $token = JWTAuth::fromUser($user, ['type' => 'user']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = ['title' => $title, 'content' => $content];
        // Arrange
        factory(Post::class, 3)->create()
            ->each(function($post) use ($user){
                factory(Comment::class, 2)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => $user->id,
                    'comment_from_type' => 'App\\User',
                ])->each(function ($comment) use ($post, $user){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => $user->id,
                        'comment_from_type' => 'App\\User',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('PUT', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('user.comments.update', [1, 1]), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'content', 'time_ago', 'author' => [], 'sub_comments' => []],
        ]);
        $response->assertJson(['data' => ['title' => $title, 'content' => $content, 'author' => ['data' => ['email' => $email]]]]);
    }

    /**
     *
     * @group users.controller.comments
     * @test
     * @return void
     */
    public function testDeleteComment()
    {
        $email = 'maraschen@codingweb.tw';
        $title = 'reply Hello LaravelConf';
        $content = 'reply Hello LaravelConf 2017.';
        $user = factory(User::class)->create([
            'email' => $email
        ]);

        $token = JWTAuth::fromUser($user, ['type' => 'user']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = ['title' => $title, 'content' => $content];
        // Arrange
        factory(Post::class, 3)->create()
            ->each(function($post) use ($user){
                factory(Comment::class, 2)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => $user->id,
                    'comment_from_type' => 'App\\User',
                ])->each(function ($comment) use ($post, $user){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => $user->id,
                        'comment_from_type' => 'App\\User',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('DELETE', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('user.comments.update', [1, 1]), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_ACCEPTED);
    }
}
