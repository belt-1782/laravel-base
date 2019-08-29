<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test the attributes that are mass assignable.
     *
     * @test
     *
     * @return void
     */
    public function test_contains_valid_fillable_properties()
    {
        $user = new User();
        $this->assertEquals(['name', 'email', 'password'], $user->getFillable());
    }

    /**
     * Test the attributes that should be hidden for arrays.
     *
     * @test
     *
     * @return void
     */
    public function test_contains_valid_hidden_properties()
    {
        $user = new User();
        $this->assertEquals(['password', 'remember_token'], $user->getHidden());
    }

    /**
     * Test the attributes that should be cast to native types.
     *
     * @test
     *
     * @return void
     */
    public function test_contains_valid_cast_properties()
    {
        $user = new User();
        $this->assertEquals(['id' => 'int', 'email_verified_at' => 'datetime'], $user->getCasts());
    }
}
