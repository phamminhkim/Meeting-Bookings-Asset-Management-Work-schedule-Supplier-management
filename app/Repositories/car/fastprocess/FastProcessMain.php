<?php 
namespace App\Repositories\car\fastprocess;

use App\Models\car\FastProcess;
use App\Models\shared\DocumentType;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class FastProcessMain extends FastProcessBase{
    public static function create($object_id,$type,$request)
    { 
        
        $obj = null;
        $module = $request->module;
        
        //dd($object_id);
     
        switch ($module) {
          
            case Ultils::$MODULE_CARS:
                //Lấy theo từng đối tượng nếu tồn tại ID
                //Nếu không có ID thì truyền các đối tượng NULL
               
                $obj = FastProcess::where('car_id',$object_id)->get();
               // dd($obj);
            default:  
                //dd($request);
                $obj= new FastProcessBase($request);
                break;
        }
      //dd( $obj);
        return  $obj;
    }
}
?>