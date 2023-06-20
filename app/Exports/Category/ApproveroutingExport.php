<?php

namespace App\Exports\Category;

use App\Models\shared\ApprovedLimit;
use App\Models\shared\ApprovedRouting;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use stdClass;

class ApproveroutingExport implements FromArray
{
    public function headings(): array
    {
        return [
            'document_type_id', 'id','approved_limit_code','group_id','team_id','description','active','name','payment_type_id' ];
        
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
      $routings = ApprovedRouting::all();
      $list = array();
     
      foreach ( $routings  as $route) {
        $arr =  array();
        $arr['company'] = $route->group->company->name;
        $arr['department'] = $route->group->department->name;
        $arr['group'] = $route->group->name;
        $arr['payment_type_id'] = $route->payment_type_id==0?"Tờ trình":($route->payment_type_id==1?"Thanh toán":($route->payment_type_id==2?"Thanh toán NVL":""));
        $team_user = $route->team->users;
        $team_usercc = $route->team->userccs;
        $approve_limit = ApprovedLimit::where('code',$route->approved_limit_code)->get()->first();
       // dd($approve_limit->document_type);
       $arr['document_type'] ="";
       $arr['name'] ="";
       $arr['budget_type'] ="";
       $arr['currency'] ="";
      // dd($approve_limit->name);
        if($approve_limit){
          $arr['document_type'] = $approve_limit->document_type?$approve_limit->document_type->code:"";
          $arr['limit_name'] = $approve_limit->name;
          $arr['budget_type'] = $approve_limit->budget_type==1?"Trong ngân sách":($approve_limit->budget_type==0?"Ngoài/Vượt ngân sách":"Không thuộc ngân sách");
          $arr['currency'] = $approve_limit->currency;
        }
       // dd( $approve_limit);
     
        foreach ($team_user as  $user) {
          $arr['username'] = $user->username;
          $arr['name'] = $user->name;
          $object= new stdClass();
         array_push($list,$this->convertArray2Object($arr));
        }

      }
      return $list;
    }

    public function convertArray2Object($defs) {
      $innerfunc = function ($a) use ( &$innerfunc ) {
         return (is_array($a)) ? (object) array_map($innerfunc, $a) : $a; 
      };
      return (object) array_map($innerfunc, $defs);
  }
}
