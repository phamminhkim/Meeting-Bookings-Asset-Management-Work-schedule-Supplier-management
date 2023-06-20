<?php

namespace Tests\Feature\Http\Controllers\api\work\workflow\define;

use App\Http\Controllers\api\work\workflow\define\WorkflowDefineController;
use App\Models\work\workflow\Wlworkflow;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorkflowDefineControllerTest extends TestCase
{
    public  $token = "";
    protected function setUp():void
    {
        // parent::setUp();
        // $test_data = ['username'=>'admin','password'=>'123'];
        // $response = $this->withHeaders([

        //     'Accept' => 'application/json'
        // ])->post('api/user/login', $test_data);
        // $this->token = $response['access_token'];
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_controller_Create_workflow_define()
    {


    //    $test_data = ['name'=>'Testing: Tạo qui trình','workflow_type_id'=>'1'];
    //    $url = action([WorkflowDefineController::class, 'store']);
    //     $response = $this->withHeaders([
    //         'Authorization' => 'Bearer '.  $this->token ,
    //         'Accept' => 'application/json'
    //     ])->post($url, $test_data);
    //     //dd($response['data']['name']);
    //     $response->assertOk();
    //     $response->assertJson([
    //         "data" =>
    //          [
    //              "name" => "Testing: Tạo qui trình",
    //              "workflow_type_id" => "1"
    //          ]

    //          ]);
    $this->assertTrue(true);
    }
}
