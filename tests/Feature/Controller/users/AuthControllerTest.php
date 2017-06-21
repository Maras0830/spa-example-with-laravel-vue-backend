<?php

namespace Tests\Feature\Controller\Users;

use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;

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

    /**
     *
     * @group users.controller.login
     * @test
     * @return void
     */
    public function testUserLoginFailed()
    {
        $email = 'maraschen@codingweb.tw';
        $password = '123456';

        factory(User::class, 1)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $response = $this->json('POST', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('user.login'), [
            'email' => $email,
            'password' => 'error_password',
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            "error" => "invalid_credentials",
            "status_code" => 401
        ]);
    }

    /**
     *
     * @group users.controller.login
     * @test
     * @return void
     */
    public function testUserGetMe()
    {
        $email = 'maraschen@codingweb.tw';
        $password = '123456';

        $user = factory(User::class)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $token = JWTAuth::fromUser($user, ['type' => 'user']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this->json('GET', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('user.me'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => ['id', 'name', 'email']
        ]);
    }


    /**
     *
     * @group users.controller.login
     * @test
     * @return void
     */
    public function testUserTokenError()
    {
        $email = 'maraschen@codingweb.tw';
        $password = '123456';

        factory(User::class)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $headers = ['Authorization' => 'bearer error'];

        $response = $this->json('GET', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('user.me'), [], $headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(["token_absent"]);
    }
}
