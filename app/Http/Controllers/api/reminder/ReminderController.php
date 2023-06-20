<?php

namespace App\Http\Controllers\api\reminder;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\shared\Reminder;
use App\Repositories\reminder\ReminderMain;
use Illuminate\Http\Request;

class ReminderController extends BaseController
{
    public function index(Request $request)
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $list = Reminder::where('user_id', auth()->user()->id)->get();

        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function store(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;

        $reminderBase = ReminderMain::create($request);

        if ($reminderBase) {

            $reminder = $reminderBase->store();
            if ($reminder) {
                $result['data']['success'] = 1;
                $result['data']['reminder'] = $reminder;

            } else {
                $result['data']['errors'] = $reminderBase->errors;
            }
        }

        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function show(Request $request, $id)
    {


        $reminder = Reminder::with('attachment_file')->find( $id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $reminder;
        $result['data']['success'] = 1;
        if (!$reminder) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function edit(Request $request, $id)
    {

        $reminderBase = ReminderMain::create($request);
        $reminderBase = $reminderBase->edit($id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $reminderBase;
        $result['data']['success'] = 1;
        if (!$reminderBase) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {

        $this->authorize('update',Reminder::find($id));

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;

        $reminderBase = ReminderMain::create($request);
        //dd($reminderBase);
        $reminder = $reminderBase->update($id);
        if ($reminder) {
            $result['data']['success'] = 1;
            $result['data']['reminder'] = $reminder;

        } else {
            $result['data']['errors'] = $reminderBase->errors;
        }
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function destroy(Request $request, $id)
    {

        $this->authorize('delete',Reminder::find($id));
        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;

        $reminderBase = ReminderMain::create($request);

        //dd($reminderBase);
        $re = $reminderBase->destroy($id);
        if ($re) {
            $result['data']['success'] = 1;


        } else {
            $result['data']['errors'] = $reminderBase->errors;
        }
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }


}
