<?php
namespace App\Traits;

use App\Models\shared\DocumentType;
use App\Models\shared\Team;
use App\User;

trait HasTeamManual{

    protected function createTeam(){
        //  protected $fillable = ['id', 'name','description','module','active','created_at','updated_at'];

        $documentType = DocumentType::find($this->request->document_type_id);
        $team = new Team();
        $team->name = $documentType->name .'_AUTO';
        $team->description = $documentType->name .'_AUTO';
        $team->module = $documentType->module;
        $team->active = '1';
        if($team->save()){
            return $team->id;
        }

        return "";
      }
      protected function createTeamCustom($name, $description, $module){
        //  protected $fillable = ['id', 'name','description','module','active','created_at','updated_at'];

        $team = new Team();
        $team->name = $name.'_CUSTOM';
        $team->description =$description;
        $team->module = $module;
        $team->active = '1';
        if($team->save()){
            return $team->id;
        }

        return "";
      }
      public function assign_user($team_id,$users){

            try {

                $team = Team::findOrFail($team_id);



                if ($team) {
                    foreach ($team->users as $key => $u) {
                         //    dd( $u['pivot'['level']]);
                             $user = User::find($u['id']);

                             $team->users()->detach($user);

                         }
                    $level = 1;
                    foreach ($users as $key => $u) {
                     //  dd($team->id );
                        $user = User::find($u['user_id']);
                        $level =  $level + 1;
                        $step = isset($u['step'])? $u['step']:"1";
                        $review = isset($u['review'])? $u['review']:null;
                         $sign = isset($u['sign'])? $u['sign']:null;
                       // $team->users()->detach($user);
                         $team->users()->attach($user,['level'=>$level,'step'=>$step,'review'=>$review,'sign'=>$sign]);

                        }
                     //$result['data']['success']  = 1;
                     return true;
                }
            } catch (\Exception $e) {
               // $result['data']['errors']  =  $e->getMessage();
               //dd( "loi");
               return false;
            }
            return false;


       // return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);


    }
}


?>
