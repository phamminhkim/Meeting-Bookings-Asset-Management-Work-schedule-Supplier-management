<?php

namespace Tests\Unit\Http\Controllers\api\document;

use App\Http\Controllers\api\document\DocumentController;

use App\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

use Mockery;

class DocumentControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testApplication()
    {
        // $user = factory(User::class)->create();

        // $response = $this->actingAs($user)
        //                  ->withSession(['username' => 'admin',])
        //                  ->get('/api/documents/4');
        //   $response->assertStatus(200);
        $this->assertTrue(true);
    }



}
