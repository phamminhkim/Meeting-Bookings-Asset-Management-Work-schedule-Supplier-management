<?php

use App\Models\shared\Team;
use App\User;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        //Nhóm IT      
        // $document_type = 'DNTT';
        // $company = '1000';
        // $payment_type = '1'; //0:Tờ trình, 1:thanh toán,2: thanh toán NVL
        // $group_code = "IT";
        // $budget_type = 1; //1:Trong ngân sách, 0 : ngoài ngân sách, 2: Vượt ngân sách, -1: Không thuộc ngân sách
        // $currency = 'VND';


        $document_type_arr=  ['DNTT','DNTU','QTTU'];
      
        $payment_type_arr=  ['1','2'];
        $group_code_arr=  ['IT','ERP'];//Trong 1 phong ban
        $budget_type_arr=  ['1','0','2'];
        foreach ($document_type_arr as $document_type) {
            foreach ($payment_type_arr as  $payment_type) {
                foreach ($group_code_arr as  $group_code) {
                    foreach ($budget_type_arr as $budget_type) {
                        $arr = array(
                            'document_type'=>$document_type,
                            'company'=>'1000',
                            'payment_type'=> $payment_type,
                            'group_code'=> $group_code,
                            'budget_type'=>$budget_type,
                            'currency'=>'VND',
                            'limit_count'=>'4',
                            'sub'=>'',
                        );
                        $this->config_team($arr);
                    }
                }
            }
        
        }

        $document_type_arr=  ['CPTK','GTTT','TTDB'];
      
        $payment_type_arr=  ['1'];
        $group_code_arr=  ['IT','ERP'];//Trong 1 phong ban
        $budget_type_arr=  ['-1'];
        foreach ($document_type_arr as $document_type) {
            foreach ($payment_type_arr as  $payment_type) {
                foreach ($group_code_arr as  $group_code) {
                    foreach ($budget_type_arr as $budget_type) {
                        $arr = array(
                            'document_type'=>$document_type,
                            'company'=>'1000',
                            'payment_type'=> $payment_type,
                            'group_code'=> $group_code,
                            'budget_type'=>$budget_type,
                            'currency'=>'VND',
                            'limit_count'=>'2',
                            'sub'=>'',
                        );
                        $this->config_team($arr);
                    }
                }
            }
        
        }
           
    }
    private function config_team($arr)
    {
        $document_type  = $arr['document_type'];
        $company        = $arr['company'];
        $payment_type   = $arr['payment_type'];
        $group_code     = $arr['group_code'];
        $budget_type    = $arr['budget_type'];
        $currency       = $arr['currency'];
        $limit_count     = $arr['limit_count'];
        $sub            = $arr['sub'];
        for ($i=1; $i <= $limit_count ; $i++) { 
            $limit = "H".$i."";
            $name = $this->create_team_name($document_type,$company,$payment_type,$group_code,$limit,$budget_type,$currency);
            $this->create_team_and_assign($limit,$sub,$name);
        }
         
    }
    private function create_team_name( $document_type,$company,$payment_type,$group_code,$limit,$budget_type,$currency){
        $name = $document_type.'_'.$company.'_'.$payment_type.'_'.$group_code.'_'.$limit.'_'.$budget_type.'_'.$currency;
        return  $name;
    }
    private function create_team_and_assign($limit,$sub,$name)
    {
        $funcname_phongban = "";
        $funcname_ketoan = "";
        $list = Team::where('name',$name)->get();
        if(!$list || $list->count()==0){
            $team = Team::create(['name'=>$name,'description'=>$name ,'active'=>'1','module'=>'PAYMENT','created_at'=>now(),'updated_at'=>now()]);
           if($team){
                $funcname_phongban =  $limit.$sub;
                $funcname_ketoan =  'assign_ketoan'.$sub;
                $this->$funcname_phongban($team);
                $this->$funcname_ketoan($team);
           }
        }
         
    }
    private function H1($team){
        $user1 = User::where('username','1001551')->first();
        $team->users()->attach($user1, $this->get_data_assign('1','1','1',''));
        $user2 = User::where('username','1000149')->first();
        $team->users()->attach($user2, $this->get_data_assign('2','1','',''));
        $user3 = User::where('username','1000778')->first();
        $team->users()->attach($user3, $this->get_data_assign('3','1','','1'));
    }
    private function H2($team){
        $user1 = User::where('username','1001551')->first();
        $team->users()->attach($user1, $this->get_data_assign('1','1','1',''));
        $user2 = User::where('username','1000149')->first();
        $team->users()->attach($user2, $this->get_data_assign('2','1','',''));
        $user3 = User::where('username','1000778')->first();
        $team->users()->attach($user3, $this->get_data_assign('3','1','','1'));
    }
    private function H3($team){
        $user1 = User::where('username','1001551')->first();
        $team->users()->attach($user1, $this->get_data_assign('1','1','1',''));
        $user2 = User::where('username','1000149')->first();
        $team->users()->attach($user2, $this->get_data_assign('2','1','',''));
        $user3 = User::where('username','1000778')->first();
        $team->users()->attach($user3, $this->get_data_assign('3','1','',''));
        $user4 = User::where('username','1000804')->first();
        $team->users()->attach($user4, $this->get_data_assign('4','1','','1'));
        
    }
    private function H4($team){
        $user1 = User::where('username','1001551')->first();
        $team->users()->attach($user1, $this->get_data_assign('1','1','1',''));
        $user2 = User::where('username','1000149')->first();
        $team->users()->attach($user2, $this->get_data_assign('2','1','',''));
        $user3 = User::where('username','1000778')->first();
        $team->users()->attach($user3, $this->get_data_assign('3','1','',''));
        $user4 = User::where('username','1000804')->first();
        $team->users()->attach($user4, $this->get_data_assign('4','1','',''));
        $user5 = User::where('username','1000001')->first();
        $team->users()->attach($user5, $this->get_data_assign('5','1','','1'));
        
    }
    private function assign_ketoan($team){
        $user1 = User::where('username','1002422')->first();
        $team->users()->attach($user1, $this->get_data_assign('1','2','',''));
        $user2 = User::where('username','1001363')->first();
        $team->users()->attach($user2, $this->get_data_assign('2','2','','2'));
      
    }
    private function get_data_assign($level,$step,$review,$sign){
        $arr =array(
            'level' => $level?$level:'1',
            'step' => $step?$step : '1',
            'review' => $review?$review:'',
            'sign' => $sign?$sign:null,
        );
        return $arr;
    } 
}
