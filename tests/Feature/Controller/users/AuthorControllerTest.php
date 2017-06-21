<?php

namespace Tests\Feature\Controller\users;

use App\Admin;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthorControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     * @group users.controller.author
     * @test
     * @return void
     */
    public function testGetAuthorList()
    {
        // Arrange
        factory(Admin::class, 10)->create();

        // Act
        $response = $this->json('GET', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('authors.index'));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['*' => ['id', 'email', 'name', 'posts' => [], 'comments' => []]],
            'meta' => ['pagination'],
        ]);
    }

    /**
     *
     * @group users.controller.author
     * @test
     * @return void
     */
    public function testGetAuthor()
    {
        // Arrange
        $email = 'maraschen@4gamers.com.tw';
        $name = 'Maras';
        factory(Admin::class, 1)->create(['email' => 'maraschen@4gamers.com.tw', 'name' => 'Maras']);
        factory(Admin::class, 10)->create();

        // Act
        $response = $this->json('GET', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('authors.show', 1));

        // Assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => ['id', 'email', 'name', 'posts' => [], 'comments' => []],
        ]);
        $response->assertJson(['data' => ['email' => $email, 'name' => $name]]);
    }
}
