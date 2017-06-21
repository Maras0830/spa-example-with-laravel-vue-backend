<?php

namespace Tests\Feature\Controller\Admins;

use App\Admin;
use App\Post;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Config;
use JWTAuth;

class PostControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPostsCreateSuccessful()
    {
        // Arrange
        $email = 'maraschen@codingweb.tw';

        $admin = factory(Admin::class)->create(['email' => $email]);

        Config::set('jwt.user', 'App\Admin');

        $token = JWTAuth::fromUser($admin, ['type' => 'admin']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = [
            'title' => 'Hello',
            'content' => 'Content',
        ];

        // Act
        $response = $this->json('POST', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.posts.store'), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'content', 'created_at', 'time_ago', 'url', 'author', 'comments'],
        ]);
    }

    public function testPostsUpdateSuccessful()
    {
        // Arrange
        $email = 'maraschen@codingweb.tw';

        $admin = factory(Admin::class)->create(['email' => $email]);
        factory(Post::class)->create(['author_id' => $admin->id]);

        Config::set('jwt.user', 'App\Admin');

        $token = JWTAuth::fromUser($admin, ['type' => 'admin']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = [
            'title' => 'Hello',
            'content' => 'Content',
        ];

        // Act
        $response = $this->json('PUT', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.posts.update', 1), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'title', 'content', 'created_at', 'time_ago', 'url', 'author', 'comments'],
        ]);
    }

    public function testPostsUpdateFailed()
    {
        // Arrange
        $email = 'maraschen@codingweb.tw';

        $admin = factory(Admin::class)->create(['email' => $email]);

        Config::set('jwt.user', 'App\Admin');

        $token = JWTAuth::fromUser($admin, ['type' => 'admin']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $except = [
            'title' => 'Hello',
            'content' => 'Content',
        ];

        // Act
        $response = $this->json('PUT', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.posts.update', 1), $except, $headers);

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonStructure([
            'message', 'status_code'
        ]);
    }
}
