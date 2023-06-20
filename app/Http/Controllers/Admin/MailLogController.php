<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\MailLog;
use App\Notifications\NotiBaseModel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailLogController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maillogs = MailLog::orderBy('date','desc')->paginate(15);

        return view('admin.maillog.index')->with('maillogs', $maillogs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $maillog = MailLog::find($id);
        return view('admin.mailLog.edit')->with([
            'maillog' => $maillog,
        ]);
    }
    public function resend($id)
    {
        $maillog = MailLog::find($id);

        $data = array('data'=>$maillog->body, 'UID'=>$maillog->hash);
        Mail::send('email.email_resend', $data, function($message) use($maillog) {
           $message->to( $maillog->to)->subject($maillog->subject);
        });
        $user = new User();
        $user = Auth::user();

        $resend  = $user->name . '('.$user->username.') : '. now();
        echo "Email đã được gửi lại cho : " .$maillog->to .'<br>';
        echo "Bởi " .$resend;
        if ($maillog->resend == '') {
            $maillog->resend   =  $resend;
        }else {
            $maillog->resend   =  $maillog->resend . ', ' . $resend;
        }

        $maillog->save();

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MailLog $maillog)
    {


        return view('admin.maillog.edit')->with([
            'maillog' => $maillog,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = "0";
        $ids = array();
        $ids = explode(',',$request->ids) ;
        array_push($ids,$id);
        try {
           // dd($ids);
            $maillogs = MailLog::whereIn('id',$ids)->get();
         // dd( $maillogs);
            foreach ($maillogs as $value) {
                $value->delete();
            }
            $result['data']['success'] = "1";
        } catch( \Exception $e) {
            $result['data']['success'] = "0";
            $result['data']['errors'] = $e->getMessage();
        }

        if ($result['data']['success'] == "1") {
            $request->session()->flash('success', 'Deleted successfully');
        } else {
            $request->session()->flash('error', 'There was an error delete the user');
        }

        return redirect()->route('adminmaillogs.index');
        // return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function search_all(Request $request)
    {

        $searchTerm  = $request->nav_search_input;

        $maillogs = MailLog::where('date', 'LIKE', "%{$searchTerm}%")
            ->orWhere('from',  'LIKE', "%{$searchTerm}%")
            ->orWhere('to',  'LIKE', "%{$searchTerm}%")
            ->orWhere('cc',  'LIKE', "%{$searchTerm}%")
            ->orWhere('subject',  'LIKE', "%{$searchTerm}%")
            ->paginate(15)->appends(request()->except('page'));

        return view('admin.maillog.index')->with(['maillogs' => $maillogs, 'searchTerm' => $searchTerm]);
    }


}
