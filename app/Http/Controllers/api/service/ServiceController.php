<?php

namespace App\Http\Controllers\api\service;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController\BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\service\Ticket;
use App\Models\service\TicketAssign;
use App\Models\shared\UserTeam;
use App\Models\service\ServiceCategory;
use App\Models\shared\File as FileTicket;
use Illuminate\Http\Request;
use App\User;
class ServiceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$userTeam = UserTeam::where('user_id', Auth::user()->id)->where('level', 1)->first();
		//$userExit = TicketAssign::whereNotIn('assign_to', Auth::user()->id)->get();
		if($userTeam){
		$tickets = Ticket::select(DB::raw('tickets.id as id, title,content,service_category_id,request_date,finish_date,finished_at,request_by,users.name as create_by,service_categories.name as name_categorie, users.name as name_assign'))
			->leftJoin('service_categories', function($join) {
				  $join->on('service_categories.id', 'tickets.service_category_id');
				})
			->leftJoin('ticket_assigns', function ($join) {
				$join->on('ticket_assigns.ticket_id', 'tickets.id');
			})
			->leftJoin('users', function($join) {
				  $join->on('users.id', 'ticket_assigns.assign_to')
				  ->where('active', 1);
				})
			->join('user_team', function ($join) {
				$join->on('user_team.team_id', 'tickets.team_id')
					 ->where('user_team.user_id', Auth::user()->id);
			})
           ->get();

		
        $result = array();
        $result['data'] = $tickets;
		if($result){
			$status = '1';
			$message = "Thành công";
		}
		else{
			$status = '1';
			$message = "Không thành công";
		}
        return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$tickets], JSON_UNESCAPED_UNICODE);
		}else{
		
		$ticket1 = DB::table('tickets')
					->join('service_categories','service_categories.id','tickets.service_category_id')
					->join('ticket_assigns','ticket_assigns.ticket_id','tickets.id')
					->join('users','users.id', 'ticket_assigns.assign_to')
					->select('tickets.id','title','content','service_categories.name as name_categorie','request_date','finish_date','users.name as name_assign')
			->where('create_by', Auth::user()->id)
			->orwhereExists(function ($query) {
					$query->select(DB::raw(1))
                     ->from('ticket_assigns')
                     ->whereColumn('ticket_assigns.ticket_id', 'tickets.id')
					 ->where('assign_to', Auth::user()->id);
					  })
			->get();
		$ticket2 = DB::table('tickets')
					->join('service_categories','service_categories.id','tickets.service_category_id')
					->select('tickets.id','title','content','service_categories.name as name_categorie','request_date','finish_date')
					->whereNotExists(function ($query) {
					$query->select(DB::raw(1))
                     ->from('ticket_assigns')
                     ->whereColumn('ticket_assigns.ticket_id', 'tickets.id');
           })
			->where('create_by', Auth::user()->id)->get();
			
			$result = array();
			$tickets = array_merge($ticket1->toArray(),$ticket2->toArray());
			$result['data'] = $tickets;
			if($result){
				$status = '1';
				$message = "Thành công";
			}
			else{
				$status = '1';
				$message = "Không thành công";
			}
        return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$tickets], JSON_UNESCAPED_UNICODE);
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$request_date = date('Y-m-d');
		$company_id = Auth::user()->company_id;
		$create_by = Auth::user()->id;
		$request_by = 4;
		$team_id = 1;
        $tickets = Ticket::create(['title' => $request->title,
                                'content' => $request->content,
                                'note' => $request->note,
								'service_category_id' => $request->service_category_id,
								'request_date' => $request_date,
								'finish_date' => $request->finish_date,
								'company_id' => $company_id,
								'project_id' => $request->project_id,
								'finished_at' => $request->finished_at,
								'create_by' => $create_by,
								'request_by' => $request_by,
								'team_id' => $team_id
								]);
		if($request->hasFile('file')){
		$filenameWithExt = $request->file('file')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('file')->getClientOriginalExtension();
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        $path = $request->file('file')->storeAs('public/images',$fileNameToStore);
		$file = new FileTicket;
		$file->user_id = Auth::user()->id;
		$file->url = $fileNameToStore;
		$tickets->files()->save($file);
		}
		$result = array();
        $result['data'] = $tickets;
		if($result){
			$status = "1";
			$message = "Thành công";
		}
		else{
			$status = "0";
			$message = "Không thành công";
		}
        return json_encode(['success'=>$status, 'message'=>$message], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tickets = DB::table('tickets')
					->leftjoin('service_categories','service_categories.id','tickets.service_category_id')
					->join('users','users.id', 'tickets.create_by','')
					->select('tickets.id','title','content','service_categories.name as name_categorie','request_date','users.name as name_create','finish_date')
					->where('tickets.id',$id)->get();
		$file = Ticket::find($id)->files()->get();
		$result = array();
        $result['data'] = $tickets;
		if($result){
			$status = "1";
			$message = "Thành công";
		}
		else{
			$status = "0";
			$message = "Không thành công";
		}
	
        return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$tickets, 'file'=>$file], JSON_UNESCAPED_UNICODE);
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
		$ticketAsg = TicketAssign::where('ticket_id', $id)->first();
		$fileExist = FileTicket::where('fileable_id', $id)->first();
		if($ticketAsg == null)
			{
				$ticket = Ticket::where('id',$id)->update($request->except(['_method','_token','file']));
				if($request->hasFile('file') && $fileExist <> null){
				$filenameWithExt = $request->file('file')->getClientOriginalName();
				$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
				$extension = $request->file('file')->getClientOriginalExtension();
				$fileNameToStore = $filename.'_'.time().'.'.$extension;
				$path = $request->file('file')->storeAs('public/images',$fileNameToStore);
				FileTicket::where('fileable_id', $id)->update(['url' => $fileNameToStore]);
				}
				else{
					if($request->hasFile('file') && $fileExist == null){
					$filenameWithExt = $request->file('file')->getClientOriginalName();
					$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
					$extension = $request->file('file')->getClientOriginalExtension();
					$fileNameToStore = $filename.'_'.time().'.'.$extension;
					$path = $request->file('file')->storeAs('public/images',$fileNameToStore);
					$file = new FileTicket;
					$file->user_id = Auth::user()->id;
					$file->url = $fileNameToStore;
					$tickets->files()->save($file);
					}
				}
				$result = array();
				$result['data'] = $ticket;
				if($result){
					$status = "1";
					$message = "Thành công";
				}
				else{
					$status = "0";
					$message = "Không thành công";
				}
			}
		else{
					$status = "0";
					$message = "Không thành công";
		}
        return json_encode(['success'=>$status, 'message'=>$message], JSON_UNESCAPED_UNICODE);
    }
public function assignTicket(Request $request){
	    $ticketExist = TicketAssign::where('ticket_id', $request->ticket_id)->first();
		$assign_by = Auth::user()->id;
		if ($ticketExist == null){
		$ticketAsg = TicketAssign::create(['ticket_id' => $request->ticket_id,
                                'assign_to' => $request->assign_to,
                                'assign_by' => $assign_by
								]);	  
				$result = array();
				$result['data'] = $ticketAsg;
				if($result){
					$status = "1";
					$message = "Thành công";
				}
				else{
					$status = "0";
					$message = "Không thành công";
				}
		}
		else{
			$ticketAsg = TicketAssign::where('ticket_id',$request->ticket_id)->update(['assign_to' => $request->assign_to,'assign_by' => $assign_by]);
				$result = array();
				$result['data'] = $ticketAsg;
				if($result){
					$status = "1";
					$message = "Thành công";
				}
				else{
					$status = "0";
					$message = "Không thành công";
				}
		}
        return json_encode(['success'=>$status, 'message'=>$message], JSON_UNESCAPED_UNICODE);
}

	public function userTeam($id){

		$status = "0";
		$message = "";
		$nameUser =[];
		$ticket = Ticket::find($id);
		$userTeam = UserTeam::where('team_id', $ticket->team_id)->get();
		
		$user_array = array();
		foreach($userTeam as $uteam)
		array_push($user_array,$uteam->user_id);
		$user = Auth::user()->id;
		$asg = null;
		$level = UserTeam::where('team_id', $ticket->team_id)->where('user_id',$user)->where('level',1)->get();
		$le1 = null;
		if($level){
		foreach($level as $lev)
			$le1 = $lev->level;
		}
		
		if(in_array($user,$user_array) && $le1 != null){
		$nameUser = User::whereIn('id',$user_array)->get();

		$ticketAsg = TicketAssign::where('ticket_id',$id)->get();
		if($ticketAsg){
		foreach($ticketAsg as $asign){
			$asg = $asign->assign_to;
		}
		}
		if($userTeam){
					$result = array();
					$result['data'] = $nameUser;
					if($result){
						$status = "1";
						$message = "Thành công";
					}
					else{
						$status = "0";
						$message = "Không thành công";
					}
		}else{
			$status = "0";
			$message = "Không thành công";
		}
		}
		return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$nameUser, 'assign'=>$asg], JSON_UNESCAPED_UNICODE);
}
	public function nameUser($id){
	$username = User::find($id);
	if($username){
			$status = "1";
			$message = "Thành công";
			$data = $username->name;
		}else{
			$status = "0";
			$message = "Không thành công";
		}
	 return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$data], JSON_UNESCAPED_UNICODE);
}
	public function serviceCategory(){
		$serviceCate = ServiceCategory::all();
				if($serviceCate){
					$result = array();
					$result['data'] = $serviceCate;
					if($result){
						$status = "1";
						$message = "Thành công";
					}
					else{
						$status = "0";
						$message = "Không thành công";
					}
		}
		return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$serviceCate], JSON_UNESCAPED_UNICODE);
	}
	public function selectserviceCategory($id){
		$ticket = Ticket::find($id);
		$serviceCate = ServiceCategory::all();
				if($serviceCate){
					$result = array();
					$result['data'] = $serviceCate;
					$cateSelected = $ticket->service_category_id;
					if($result){
						$status = "1";
						$message = "Thành công";
					}
					else{
						$status = "0";
						$message = "Không thành công";
					}
		}
		return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$serviceCate, 'cate'=>$cateSelected], JSON_UNESCAPED_UNICODE);
	}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticketAsg = TicketAssign::where('ticket_id', $id)->first();
		if($ticketAsg){
			$status = "0";
			$message = "Không thành công";
		}else{
			$ticket = Ticket::where('id',$id)->delete();
			$file = FileTicket::where('fileable_id', $id)->delete();
			$status = "1";
			$message = "Thành công";
		}
		 return json_encode(['success'=>$status, 'message'=>$message], JSON_UNESCAPED_UNICODE);
    }
}
