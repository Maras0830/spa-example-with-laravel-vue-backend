<?php

namespace Tests\Feature\Controller\Users;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     * @group users.controller.login
     * @test
     * @return void
     */
    public function testUserLoginSuccessAndGetToken()
    {
        $email = 'maraschen@codingweb.tw';
        $password = '123456';

        factory(User::class, 1)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $response = $this->json('POST', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('user.login'), [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'token',
            'token_ttl',
            'user' => ['id', 'name', 'email']
        ]);
    }
}
