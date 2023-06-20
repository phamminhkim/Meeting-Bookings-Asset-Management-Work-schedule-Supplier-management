<?php

namespace Tests\Unit\Repositories\work\workflow\define;

use App\Models\work\workflow\Wlworkflow;
use App\Repositories\work\workflow\define\WorkflowDefineMain;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class WorkflowDefineMainTest extends TestCase
{
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }
    // /**
    //  * A basic unit test example.
    //  *
    //  * @return void
    //  */
    // // public function test_model_workflow()
    // // {
    // //     $workflow = factory(Wlworkflow::class)->create();
    // //     $this->assertInstanceOf(Wlworkflow::class,$workflow);
    // // }
    // public function test_unit_not_create_new_workflow()
    // {
    //     $data_test = [
    //         "name"=>"Testing: Tạo qui trình",
    //         "workflow_type_id"=>"1",
    //         "phases" =>[
    //                [
    //                    "name" => "Khởi tạo chứng từ",
    //                    "description" => "Khởi tạo chứng từ",
    //                    "time_execution" => "1",
    //                    "jobs" => [
    //                       [
    //                         "name" => "Tạo yêu cầu",
    //                         "action_user" => "1",
    //                         "description" => "tạo chứng từ"
    //                       ]
    //                    ]
    //                ],
    //                [
    //                 "name" => "Phê duyệt chứng từ",
    //                 "description" => "Phê duyệt chứng từ",
    //                 "time_execution" => "1",
    //                 "jobs" => [
    //                    [
    //                     "name" => "Duyệt chứng từ",
    //                     "action_user" => "2",
    //                     "description" => "Chấp nhận chứng từ"
    //                    ]
    //                 ]
    //                ],
    //                [
    //                 "name" => "Báo cáo",
    //                 "description" => "Báo cáo",
    //                 "time_execution" => "1",
    //                 "jobs" => [
    //                    [
    //                     "name" => "Nhập thông tin báo cáo",
    //                     "action_user" => "3",
    //                     "description" => "Nhập thông tin báo cáo"
    //                    ]
    //                 ]
    //             ]

    //         ]
    //      ];
    //     $request = new Request( $data_test);
    //     $workflowBase = WorkflowDefineMain::create($request);
    //     $workflow  =  $workflowBase->store();
    //     //dd($workflow);
    //     $this->assertNotEmpty($workflow);
    //    // dd($workflow);
    //     $this->assertInstanceOf(Wlworkflow::class,$workflow);
    // }
}
