<?php

namespace Tests\Feature\Controller\users;

use App\Admin;
use App\Comment;
use App\Post;
use App\User;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use JWTAuth;
use Hash;
use Config;

class PostControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     * @group users.controller.posts
     * @test
     * @return void
     */
    public function testPostList()
    {
        // Arrange
        factory(Post::class, 10)->create()
            ->each(function($post) {
                factory(Comment::class, 2)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => factory(Admin::class)->create()->id,
                    'comment_from_type' => 'App\\Admin',
                ]);
                factory(Comment::class, 8)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => factory(User::class)->create()->id,
                    'comment_from_type' => 'App\\User',
                ])->each(function ($comment) use ($post){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => factory(User::class)->create()->id,
                        'comment_from_type' => 'App\\User',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('GET', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('posts.index'));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['*' => ['id', 'title', 'content', 'created_at', 'time_ago', 'url', 'author', 'comments' => []]],
            'meta' => ['pagination' => []],
        ]);
    }

    /**
     *
     * @group users.controller.posts
     * @test
     * @return void
     */
    public function testPosts()
    {
        // Arrange
        factory(Post::class, 10)->create()
            ->each(function($post) {
                factory(Comment::class, 2)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => factory(Admin::class)->create()->id,
                    'comment_from_type' => 'App\\Admin',
                ]);
                factory(Comment::class, 8)->create([
                    'post_id' => $post->id,
                    'comment_from_id' => factory(User::class)->create()->id,
                    'comment_from_type' => 'App\\User',
                ])->each(function ($comment) use ($post){
                    factory(Comment::class, 2)->create([
                        'post_id' => $post->id,
                        'comment_from_id' => factory(User::class)->create()->id,
                        'comment_from_type' => 'App\\User',
                        'comment_id' => $comment->id,
                    ]);
                });
            });

        // Acc
        $response = $this->json('GET', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('posts.show', 1));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'content', 'created_at', 'time_ago', 'url', 'author', 'comments'],
        ]);
    }

    /**
     *
     * @group users.controller.posts
     * @test
     * @return void
     */
    public function testPostsNotFound()
    {
        // Arrange

        // Acc
        $response = $this->json('GET', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('posts.show', 1));

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonStructure([
            'message', 'status_code'
        ]);
    }

}
