<?php 
namespace App\Repositories\car\causemeasure;

use App\Models\car\CauseMeasure;
use App\Models\shared\DocumentType;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class CauseMeasureMain extends CauseMeasureBase{
    public static function create($type,$object_id,$request)
    { 
        
        $approve = null;
        $obj = null;
        $team = null;
        $list_approved = null;
        $module = $type;
        //dd($object_id);
        switch ($module) {
          
            case Ultils::$MODULE_CARS:
                //Lấy theo từng đối tượng nếu tồn tại ID
                //Nếu không có ID thì truyền các đối tượng NULL
               
                $obj = CauseMeasure::where('car_id',$object_id)->get();
            default:  
         
                $obj= new CauseMeasureBase($request);
                break;
        }
      
        return  $obj;
    }
}
?>