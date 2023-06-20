<?php

namespace App\Http\Controllers\api\service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\service\Ticket;
use App\Models\service\TicketReport;
use App\Models\service\TicketAssign;
use App\Models\service\Team;
use Illuminate\Support\Facades\Auth;

use App\Models\shared\Comment;
use App\Models\shared\ReviewRating;


use DB;
class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $ticket = new Ticket();
       $ticket->title					=	"Mua may tinh 1"			;
       $ticket->content                = "may tinh moi";
       $ticket->note                   = "";
       $ticket->id                   = 2;
       $ticket->service_category_id    = 1;
       $ticket->request_date           = "2020-01-01 00:00:00";
       $ticket->finish_date            = "2020-01-01 00:00:00";
       $ticket->company_id            = 1;
       $ticket->project_id             = 1;
    //    $ticket->created_at              = "2020-01-01 00:00:00";
    //    $ticket->updated_at              = "2020-01-01 00:00:00";
       $ticket->finished_at            = "2020-01-01 00:00:00";
       $ticket->create_by              = 1;
       $ticket->request_by             = 1;
       $ticket->team_id                = 1;

       $ticket->save();

    }
    public function showall()
    {        
      $result = DB::table('tickets')->get()->toArray();   
    
     return json_encode($result, JSON_UNESCAPED_UNICODE);  
    }
    public function getbyTicketId(Request $request)
    {
      $id=$request->input('id');;
        $tickets = DB::table('tickets')
					->leftjoin('service_categories','service_categories.id','tickets.service_category_id')
					->join('users','users.id', 'tickets.create_by','')
					->select('tickets.id','title','content','service_categories.name as name_categorie','request_date','users.name as name_create','finish_date')
					->where('tickets.id',$id)->get();
		$file = Ticket::find($id)->files()->get();
    $comments=Ticket::find($id)->comments;
    $report= DB::table('ticket_reports')  
    ->where('ticket_reports.ticket_id',$id)->first();   
     $reports=DB::table('ticket_reports')  
              ->where('ticket_reports.ticket_id',$id)->get();       
    $ratings=Ticket::find($id)->reviewrating;
    $rating=Ticket::find($id)->reviewrating->last();
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
	
        return json_encode(['success'=>$status, 'message'=>$message, 'data'=>$tickets,'reviewratings'=>$ratings,'reviewrating'=>$rating,'reports'=>$reports,'report'=>$report,'comments'=>$comments, 'file'=>$file], JSON_UNESCAPED_UNICODE);
    }
    public function listForUserAssign(Request $request)
    {
     $userid =Auth::user()->id;//$request->input('user_id');
     //dd(Auth::user()->id);
      $result = array();            
     $result['message'] = "phân công công việc thành công";
     $result['data'] = DB::table('tickets')
     -> join ('ticket_assigns', 'tickets.id', '=', 'ticket_assigns.ticket_id')   
      -> where ('ticket_assigns.assign_to', $userid)  
      -> get();
        return json_encode($result, JSON_UNESCAPED_UNICODE);   
    }
  //Tickets get by user create
  public function listForUserCreate(Request $request)
  {
      $result = array();
     // $user = $request->input('userid');  
      $user =Auth::user()->id;      
      $ticket= DB::table('tickets')
     -> where ('create_by', $user)     
     -> get();  
      $result['data'] =$ticket;     
     return json_encode($result, JSON_UNESCAPED_UNICODE);  
  }

  public function comment(Request $request)
  {   

    $ticket = Ticket::find($request->input('ticket_id'));
    // $commentable_id = $request->input('ticket_id');
    $content =  $request->input('content');
    $parent_id = $request->input('parent_id');   
    $user_id = Auth::user()->id;
    $commentable_type = 'app\Models\service\Ticket'; 
;
     $obj = new comment();
    // $obj->commentable_id   =   $commentable_id ;
    // $obj->commentable_type   =   $commentable_type ;
    $obj->content           =   $content ;
    $obj->parent_id            =   $parent_id ;
    $obj->user_id           =   $user_id ;   
    
    $res =  $ticket->comments()->save($obj);
    // $res=  $obj->save();
    if ($res)
    $result["success"]= 1;
    else
    $result["success"]= 0;

   $result["message"] = "Đánh giá xếp hạng thành công";   
      
     return json_encode($result, JSON_UNESCAPED_UNICODE); 
  }
  
  public function listComment(Request $request)
  {
    //Get ticket Id from request
    $ticket_id = $request->input('ticket_id');
  //get Ticket model by ticket  ID
    $tk = Ticket::find($ticket_id);
    //get all comments by ticket id
  
    if($tk)
    {
      $cmt= $tk->comments;      
      $result['success'] =1;
      $result['message'] ="Đánh giá xếp hạng thành công";
     // $result['message'] ="Đánh giá xếp hạng thành công";
      $result['ticket_id'] =$ticket_id;
      $result['comments'] =$cmt;   
    }
    else
    $result['success'] =0;

    //return
      return json_encode($result, JSON_UNESCAPED_UNICODE);    
  }
  public function reportResult(Request $request)
  {
    $ticket_id = $request->input('ticket_id');  
    $user_id = Auth::user()->id; // $request->input('user_id');
    $content = $request->input('content');

    $ticket = new TicketReport();
    $ticket->ticket_id					=	$ticket_id;
    $ticket->user_Id           =   $user_id ;
    $ticket->content           =   $content ;

    $res=$ticket->save();
    if ($res)
    $result["success"]= 1;
    else
    $result["success"]= 0;
   $result["message"] = "Đánh giá xếp hạng thành công";
    
    return json_encode($result, JSON_UNESCAPED_UNICODE); 
  }
  public function reviewsRating(Request $request)
  {  
    $objectable_type = 'app\Models\service\Ticket';//$request->input('objectable_type');
    $reviews = $request->input('reviews');
    $rating = $request->input('rating');
    $userid = Auth::user()->id;  //$request->input('user_id');
    $ticket_id = $request->input('objectable_id');    
;
     $obj = new reviewRating();
    $obj->objectable_type   =   $objectable_type ;
    $obj->reviews           =   $reviews ;
    $obj->rating            =   $rating ;
    $obj->user_id           =   $userid ;
    $obj->objectable_id           =   $ticket_id ;    

    $res=  $obj->save(); 
    if ($res)
    $result["success"]= 1;
    else
    $result["success"]= 0;
   $result["message"] = "Đánh giá xếp hạng thành công";    
    return json_encode($result, JSON_UNESCAPED_UNICODE); 
  
  }
  
  public function listreviewsRating(Request $request)
  {
    //Get ticket Id from request
    $ticketid = $request->input('ticketid');
  //get Ticket model by ticket ID
    $tk = Ticket::find($ticketid);
    //get all comments by ticket id
    $cmt= $tk->reviewrating;    
    //return
      return json_encode($cmt, JSON_UNESCAPED_UNICODE);    
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
       
      $ticketasign = new TicketAssign();
       $ticketasign->ticket_id= $request->input('id');  
       $ticketasign->assign_to = $request->input('user');
       $ticketasign->assign_by = $request->input('user');
       $ticketasign->save();
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
    public function destroy($id)
    {
        //
    }
}
