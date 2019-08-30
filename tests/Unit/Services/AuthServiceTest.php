<?php

namespace Tests\Unit\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\AuthService;
use App\User;

class AuthServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var AuthService
     */
    protected $authService;

    public function setUp():void
    {
        parent::setUp();

        $this->authService = new AuthService();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_create_user_success()
    {
        $request = new SignupRequest();
        $request['name'] = 'Le Thi Be';
        $request['email'] = 'le.thi.be@sun-asterisk.com';
        $request['password'] = '123456';
        $user = $this->authService->createUser($request);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($request['name'], $user['name']);
        $this->assertEquals($request['email'], $user['email']);
    }

    public function test_login_success()
    {
        $user = factory(User::class)->create([
            'name' => 'Le Thi Be',
            'email' =>'le.thi.be@sun-asteisk.com',
            'password' => bcrypt($password = '123456')
        ]);

        $request = new LoginRequest();
        $request['email'] = 'le.thi.be@sun-asteisk.com';
        $request['password'] = '123456';
        $response = $this->authService->login($request);
        $this->assertIsArray($response);
        $this->assertInstanceOf(User::class, $response['user']);
        $this->assertEquals($request['email'], $response['user']['email']);
    }
}
