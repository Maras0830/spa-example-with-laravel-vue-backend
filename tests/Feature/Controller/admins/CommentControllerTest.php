<?php

namespace Tests\Feature\Controller\admins;

use App\Admin;
use App\Comment;
use App\Post;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use Config;

class CommentControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     * @group admins.controller.comments
     * @test
     * @return void
     */
    public function testPostSubComment()
    {
        // Arrange
        Config::set('jwt.user', 'App\Admin');
        $email = 'maraschen@codingweb.tw';
        $title = 'reply Hello LaravelConf';
        $content = 'reply Hello LaravelConf 2017.';
        $admin = factory(Admin::class)->create([
            'email' => $email
        ]);

        $token = JWTAuth::fromUser($admin, ['type' => 'admin']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = ['title' => $title, 'content' => $content];

        factory(Post::class, 3)->create()
            ->each(function($post) use ($admin){
                factory(Comment::class, 8)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => $admin->id,
                    'comment_from_type' => 'App\\Admin',
                ])->each(function ($comment) use ($post, $admin){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => $admin->id,
                        'comment_from_type' => 'App\\Admin',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('POST', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.comments.store.sub', [1, 1]), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'content', 'time_ago', 'author' => [], 'sub_comments' => []],
        ]);
        $response->assertJson(['data' => ['title' => $title, 'content' => $content, 'author' => ['data' => ['email' => $email]]]]);
    }
    
    /**
     *
     * @group admins.controller.comments
     * @test
     * @return void
     */
    public function testPostComment()
    {
        // Arrange
        Config::set('jwt.user', 'App\Admin');
        $email = 'maraschen@codingweb.tw';
        $title = 'reply Hello LaravelConf';
        $content = 'reply Hello LaravelConf 2017.';
        $admin = factory(Admin::class)->create([
            'email' => $email
        ]);

        $token = JWTAuth::fromUser($admin, ['type' => 'admin']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = ['title' => $title, 'content' => $content];

        factory(Post::class, 3)->create()
            ->each(function($post) use ($admin){
                factory(Comment::class, 8)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => $admin->id,
                    'comment_from_type' => 'App\\Admin',
                ])->each(function ($comment) use ($post, $admin){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => $admin->id,
                        'comment_from_type' => 'App\\Admin',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('POST', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.comments.store', 1), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'content', 'time_ago', 'author' => [], 'sub_comments' => []],
        ]);
        $response->assertJson(['data' => ['title' => $title, 'content' => $content, 'author' => ['data' => ['email' => $email]]]]);
    }

    /**
     *
     * @group admins.controller.comments
     * @test
     * @return void
     */
    public function testPutComment()
    {
        // Arrange
        Config::set('jwt.user', 'App\Admin');
        $email = 'maraschen@codingweb.tw';
        $title = 'reply Hello LaravelConf';
        $content = 'reply Hello LaravelConf 2017.';
        $admin = factory(Admin::class)->create([
            'email' => $email
        ]);

        $token = JWTAuth::fromUser($admin, ['type' => 'admin']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = ['title' => $title, 'content' => $content];

        factory(Post::class, 3)->create()
            ->each(function($post) use ($admin){
                factory(Comment::class, 2)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => $admin->id,
                    'comment_from_type' => 'App\\Admin',
                ])->each(function ($comment) use ($post, $admin){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => $admin->id,
                        'comment_from_type' => 'App\\Admin',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('PUT', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.comments.update', [1, 1]), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'content', 'time_ago', 'author' => [], 'sub_comments' => []],
        ]);
        $response->assertJson(['data' => ['title' => $title, 'content' => $content, 'author' => ['data' => ['email' => $email]]]]);
    }

    /**
     *
     * @group admins.controller.comments
     * @test
     * @return void
     */
    public function testDeleteComment()
    {
        // Arrange
        Config::set('jwt.user', 'App\Admin');
        $email = 'maraschen@codingweb.tw';
        $title = 'reply Hello LaravelConf';
        $content = 'reply Hello LaravelConf 2017.';
        $admin = factory(Admin::class)->create([
            'email' => $email
        ]);

        $token = JWTAuth::fromUser($admin, ['type' => 'admin']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = ['title' => $title, 'content' => $content];

        factory(Post::class, 3)->create()
            ->each(function($post) use ($admin){
                factory(Comment::class, 2)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => $admin->id,
                    'comment_from_type' => 'App\\Admin',
                ])->each(function ($comment) use ($post, $admin){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => $admin->id,
                        'comment_from_type' => 'App\\Admin',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('DELETE', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.comments.update', [1, 1]), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_ACCEPTED);
    }
}
