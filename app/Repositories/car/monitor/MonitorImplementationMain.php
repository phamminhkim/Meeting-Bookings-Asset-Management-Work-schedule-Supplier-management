<?php 
namespace App\Repositories\car\monitor;

use App\Models\car\MonitorImplementation;
use App\Models\shared\DocumentType;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class MonitorImplementationMain extends MonitorImplementationBase{
    public static function create($object_id,$type,$request)
    { 
        
        $obj = null;
        $module = $request->module;
        
        //dd($object_id);
     
        switch ($module) {
          
            case Ultils::$MODULE_CARS:
                //Lấy theo từng đối tượng nếu tồn tại ID
                //Nếu không có ID thì truyền các đối tượng NULL
               
                $obj = MonitorImplementation::where('car_id',$object_id)->get();
               // dd($obj);
            default:  
         
                $obj= new MonitorImplementationBase($request);
                break;
        }
      //dd( $obj);
        return  $obj;
    }
}
?>