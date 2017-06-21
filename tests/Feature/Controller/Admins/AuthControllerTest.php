<?php

namespace Tests\Feature\Controller\Admins;

use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use JWTAuth;
use Config;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     * @group admins.controller.login
     * @test
     * @return void
     */
    public function testAdminLoginSuccessAndGetToken()
    {
        $email = 'maraschen@codingweb.tw';
        $password = '123456';

        factory(Admin::class, 1)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $response = $this->json('POST', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.login'), [
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'token',
            'token_ttl',
            'admin' => ['id', 'name', 'email']
        ]);
    }

    /**
     *
     * @group admins.controller.login
     * @test
     * @return void
     */
    public function testUserLoginFailed()
    {
        $email = 'maraschen@codingweb.tw';
        $password = '123456';

        factory(Admin::class, 1)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $response = $this->json('POST', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.login'), [
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
     * @group admins.controller.login
     * @test
     * @return void
     */
    public function testAdminGetMe()
    {
        $email = 'maraschen@codingweb.tw';
        $password = '123456';

        $admin = factory(Admin::class)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        Config::set('jwt.user', 'App\Admin');

        $token = JWTAuth::fromUser($admin, ['type' => 'admin']);

        $headers = ['Authorization' => 'Bearer ' . $token];

        $response = $this->json('GET', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.me'), [], $headers);

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'data' => ['id', 'name', 'email']
        ]);
    }

    /**
     *
     * @group admins.controller.login
     * @test
     * @return void
     */
    public function testUserTokenError()
    {
        $email = 'maraschen@codingweb.tw';
        $password = '123456';

        factory(Admin::class)->create([
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $headers = ['Authorization' => 'bearer error'];

        $response = $this->json('GET', app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('admin.me'), [], $headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(["token_absent"]);
    }
}
