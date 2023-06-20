<?php
namespace App\Repositories\commentvote;

use App\Models\car\Car;
use App\Models\shared\CommentVote;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class CommentVoteMain extends CommentVoteBase
{

    public static function create(Request $request  )
    {

        $obj = null;
        $module =   $request->module;
        $commentvoteBase = null;

       // dd($request->module);
        switch ($module) {
            case Ultils::$MODULE_CARS:

                $obj = Car::find($request->object_id);
               

               break;
            default:

                break;
        }
        //dd($obj );
        if($obj){
            $commentvoteBase = new CommentVoteBase($request,$obj);
          

        }
       
        return $commentvoteBase;
    }
}
