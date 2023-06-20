<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\tmdt\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Models\service\Ticket;
use App\Models\shared\Comment;
use App\Notifications\CommentToTicket;
class ProductController extends BaseController
{
    public function testLaytout(){
        return view('homenew');
    }
    public function testNoti(Request $request){
        
        $ticket = new Ticket();
        $ticket->title					=	"Mua may tinh 1"			;
        $ticket->content                = "may tinh moi";
        $ticket->note                   = "";
        $ticket->service_category_id    = 1;
        $ticket->request_date           = "2020-01-01 00:00:00";
        $ticket->finish_date            = "2020-01-01 00:00:00";
        $ticket->company_id            = 1;
        $ticket->project_id             = 1;
     //    $ticket->created_at              = "2020-01-01 00:00:00";
     //    $ticket->updated_at              = "2020-01-01 00:00:00";
        $ticket->finished_at            = "2020-01-01 00:00:00";
        $ticket->create_by              = 214;//cần điều chỉnh : user phải tồn tại trong bảng
        $ticket->request_by             = 4;
        $ticket->team_id                = 1;
 
        $ticket->save();
       
       $comment = new Comment();
       $comment->content = "Good luck day ";
       $ticket->comments()->save($comment);
      
       $ticket->createBy->notify(new CommentToTicket($ticket));
       
      // dd(auth()->user()->token());
       return ('Gui thanh cong');

    }
    public function createProduct(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product);
    }
    public function updateProduct(Request $request, $id)
    {
        $product  = FacadesDB::table('products')->where('pid', $request->input('pid'))->get();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->save();
        $response["products"] = $product;
        $response["success"] = 1;
        return response()->json($response);
    }
    public function deleteProduct($id)
    {
        $product  = DB::table('products')->where('pid', $request->input('pid'))->get();
        $product->delete();
        return response()->json('Removed successfully.');
    }
    public function index()
    {
        $products  = Product::all();
        $response["products"] = $products;
        $response["success"] = 1;
        return response()->json($response);
    }
}
