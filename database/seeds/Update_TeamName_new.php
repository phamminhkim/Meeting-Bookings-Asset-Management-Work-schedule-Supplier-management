<?php

use App\Models\shared\ApprovedLimit;
use App\Models\shared\ApprovedRouting;
use App\Models\shared\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class Update_TeamName_new extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $routings = ApprovedRouting::all();
        foreach ($routings as $routing) {
            $limit  =  ApprovedLimit::where('code',$routing->approved_limit_code)->first();
           
          
            if($routing->document_type && $limit && $routing->group->department){
                $teamname = $this->create_team_name($routing->document_type->code,$routing->group->company_id,$routing->group->department->code,$routing->payment_type_id,
                $routing->group->name,$limit->name, $limit ->budget_type, $limit ->currency);

                $team = Team::findOrFail($routing->team_id);
                //dd( $teamname);
                if( $team != null ){
                     
                   
                    //Log::info($team->name. ",".$teamname);
                    $team->name = $teamname;
                    $team->description = $teamname;
                    $team->save();
                }
            }
        }
    }
    private function create_team_name( $document_type,$company,$department_code,$payment_type
    ,$group_code,$limit,$budget_type,$currency){
        $name = $document_type.'_'.$company.'_'. $department_code .'_'.$payment_type.'_'.$group_code.'_'.$limit.'_'.$budget_type.'_'.$currency;
        return  $name;
    }
   
}
