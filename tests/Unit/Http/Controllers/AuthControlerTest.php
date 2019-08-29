<?php

namespace Tests\Unit\Http\Controllers;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    use WithFaker, DatabaseTransactions;

    /**
     * Test sign up user success.
     *
     * @test
     *
     * @return void
     */
    public function test_signup_success()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => '123123',
            'password_confirmation' => '123123',
        ];

        $response = $this->json('POST', '/api/auth/signup', $data);
        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'user'
                 ]);
    }

    /**
     * Test validate sign up user.
     *
     * @param $paramsInput
     * @param $expectedResult
     *
     * @dataProvider providerTestSignupUser
     */
    public function test_validate_for_signup($paramsInput, $expectedResult)
    {
        $response = $this->json('POST', '/api/auth/signup', $paramsInput);
        $response->assertStatus(400)
            ->assertJson($expectedResult);
    }

    /**
     * Test login user success.
     *
     * @test
     *
     * @return void
     */
    public function test_login_success()
    {
        $user = factory(User::class)->create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($password = '123456')
        ]);

        $response = $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'expires_at'
                 ]);
    }

    /**
     * Test login user fail.
     *
     * @test
     *
     * @return void
     */
    public function test_login_fail()
    {
        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'example@gmail.com',
            'password' => '123456',
        ]);
        $response->assertStatus(401)
            ->assertJson([
                 'message' => 'Unauthorized'
            ]);
    }

    /**
     * Test validate login user.
     *
     * @param $paramsInput
     * @param $expectedResult
     *
     * @dataProvider providerTestLoginUser
     */
    public function test_validate_for_login($paramsInput, $expectedResult)
    {
        $response = $this->json('POST', 'api/auth/login', $paramsInput);
        $response->assertStatus(400)
            ->assertJson($expectedResult);
    }

    /**
     * Test logout user success.
     *
     * @test
     *
     * @return void
     */
    public function test_logout_success()
    {
        $user = factory(User::class)->create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt($password = '123456')
        ]);

        $token = $user->createToken('Personal Access Token')->accessToken;
        $response = $this->json('GET', '/api/auth/logout', [], ['Authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200)
            ->assertJson([
                "message" => "Successfully logged out",
            ]);
    }

    /**
     * Test logout user fail.
     *
     * @test
     *
     * @return void
     */
    public function test_logout_fail()
    {
        $response = $this->json('GET', '/api/auth/logout', [], ['Authorization' => 'Bearer ' . "1234567"]);
        $response->assertStatus(400)
            ->assertJson([
                "success" => false,
                "error" => [
                    "code" => 601,
                    "message" => "Unauthorized, please check your credentials."
                ]
            ]);
    }

    public function providerTestSignupUser()
    {
        return [
            [
                [
                    'name' => 'example-name',
                    'password' => '123123',
                    'password_confirmation' => '123123',
                ],
                [
                    'success' => false,
                    'error' => [
                        'code'=> 622,
                        'message' => 'The email field is required.',
                    ]
                ]
            ],
            [

                [
                    'name' => 'example-name',
                    'email' => 'example-email',
                    'password' => '123123',
                    'password_confirmation' => '123123',
                ],
                [
                    'success' => false,
                    'error' => [
                        "code"=> 622,
                        "message" => "The email must be a valid email address.",
                    ]
                ]
            ],
            [
                [
                    'name' => 'example-name',
                    'email' => 'example@gmail.com',
                ],
                [
                    'success' => false,
                    'error' => [
                        "code"=> 622,
                        "message" => "The password field is required.",
                    ]
                ],
            ],
            [
                [
                    'email' => 'example@gmail.com',
                    'password' => '123123',
                    'password_confirmation' => '123123',
                ],
                [
                    'success' => false,
                    'error' => [
                        "code"=> 622,
                        "message" => "The name field is required.",
                    ]
                ]
            ]

        ];
    }

    public function providerTestLoginUser(){
        return [
            [
                [
                    'password' => '123456',
                ],
                [
                    'success' => false,
                    'error' => [
                        "code"=> 622,
                        "message" => "The email field is required.",
                    ]
                ]
            ],
            [
                [
                    'email' => 'example@gmail.com',
                ],
                [
                    'success' => false,
                    'error' => [
                        "code"=> 622,
                        "message" => "The password field is required.",
                    ]
                ]
            ]
        ];
    }
}
