<?php 
namespace App\Repositories\car\evaluate;

use App\Models\car\ResultEvaluation;
use App\Models\shared\DocumentType;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class ResultEvaluationMain extends ResultEvaluationBase{
    public static function create($object_id,$type,$request)
    { 
        
        $obj = null;
        $module = $request->module;
        
        //dd($object_id);
     
        switch ($module) {
          
            case Ultils::$MODULE_CARS:
                //Lấy theo từng đối tượng nếu tồn tại ID
                //Nếu không có ID thì truyền các đối tượng NULL
               
                $obj = ResultEvaluation::where('car_id',$object_id)->get();
               // dd($obj);
            default:  
         
                $obj= new ResultEvaluationBase($request);
                break;
        }
      //dd( $obj);
        return  $obj;
    }
}
?>