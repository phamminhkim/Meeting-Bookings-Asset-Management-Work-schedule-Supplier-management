<?php
    namespace App\Repositories\approvewf;

use App\Models\car\Car;
use App\Repositories\approvewf\ApproveCar;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

    final class ApproveMain  {

        public static function create($type,$object_id,$request)
        {
            $approve = null;
            $obj = null;
            $team = null;
            $list_approved = null;
            $module = $type;
           // dd($module);
            //dd( '11111');
            //Cấu hình các loại chứng từ cụ thể sẽ phát triển sau  vào bên dưới
            //Mỗi chứng từ sẽ tương ứng 1 class con kế thừa từ class ApproveAbs
            switch ($module) {
                    case Ultils::$MODULE_CARS:
                    //Lấy theo từng đối tượng nếu tồn tại ID
                    //Nếu không có ID thì truyền các đối tượng NULL
                    
                    $obj = Car::find($object_id);
                    
                    if($obj){
                    //    $team = Team::findOrFail($obj->team_id);
                        $team = null;
                       $list_approved = $obj->approveds;
                      // $user_approved = $request->user_id;
                     
                    }
                   // dd($request->$request->wfapproved);
                    $approve = new ApproveCar($team,$obj,$list_approved,$request->wfapproved,$request->infos, $request->car_error_id,$request->cause,$request->risk, $request->is_cause_measure,$request->is_type_car);

                    break;

                default: 

                    break;
            }
           // dd($approve);
            return $approve;
        }
    }


?>
