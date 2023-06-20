<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRedirectWithNoLoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function testUserCanViewLogin()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);

//        $response->assertViewIs('auth.login')->assertSee('Login');
    }
    public function testExample()
    {
        $response = $this->get('/home');

        $response->assertRedirect('/login');
    }
    public function testUserCanViewRegister()
    {
        $response = $this->get('/register');

        $response->assertStatus(302);
       // $response->assertViewIs('auth.register')->assertSee('register');
    }
    public function testCanRegister()
    {
        $this->assertGuest();
        $user = factory(User::class)->make();

        $response = $this->post('/register', [
            'name' => $user->name,
            'username' => $user->username,
            'password' => '123',
            'password_confirmation' => '123'
        ]);

      //  $response->assertStatus(302)->assertRedirect('/home');
//        $this->assertAuthenticated();
    }
}
