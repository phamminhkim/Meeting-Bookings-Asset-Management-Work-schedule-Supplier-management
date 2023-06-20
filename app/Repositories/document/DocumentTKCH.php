<?php
namespace App\Repositories\document;

use App\Models\document\Document;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class DocumentTKCH extends DocumentBase{ //Trình ký chung

    public function __construct(Request $request)
    {
        parent::__construct( $request);
        //$this->form_name = __('Tờ trình');

    }

    public  function getLayout(){

        parent::getLayout();

        $control = new HtmlAtrtibute();
        $control->require_validation = false;
        $this->layout->amount = $control;

        $control = new HtmlAtrtibute();

        $control->require_validation = true;
        $this->layout->docbrowser_type_id = $control;

        $control = new HtmlAtrtibute();

        $control->require_validation = false;
        $this->layout->filesign = $control;


        return $this->layout;
    }
    protected function validate_store(){

        if (!isset($this->request['amount']) ) {
            $this->request['amount'] = 0;
        }

        $this->request['budget_type'] = 1;
        $validator  = parent::validate_store();
        $is_sign = false;
         foreach ($this->request->team_users as  $value) {
            if ($value['user_id'] != '' && $value['step'] == '3' && $value['sign'] == '1') {
                $is_sign = true;
             }
         }
        if (!$is_sign) {
              $validator->after(function ($validator) {
                    $validator->errors()->add('sign', 'Vui lòng chọn người phê duyệt.');
                });
        }

        $list = array();
        foreach ($this->request->team_users as  $value) {
            array_push($list ,$value['user_id']);
        }
        if ( Ultils::is_duplicate($list)   ) {
            $validator->after(function ($validator) {
                $validator->errors()->add('sign', 'Trùng user trong cây duyệt.');
            });
        }

        $flag_pdf = false;
        $user_fails = new User;
       // dd($this->request->filesigns);
        foreach ($this->request->filesigns as  $filesign) {
            $signs = [];
            $file_name = $filesign['attachment_file'][0]['name'];
            if (isset( $filesign['attachment_file'][0]['signs'])) {
                $signs = $filesign['attachment_file'][0]['signs'];
            }
            if (count($signs) > 0) {

                foreach ($signs as $sign) {

                    $flag_pdf =   $this->check_team_approve($sign['sign']);

                    if (!$flag_pdf  && auth()->user()->id != $sign['sign']) {
                        $user_fails  = User::find($sign['sign']);
                        $validator->after(function ($validator) use($user_fails,$file_name) {
                            $validator->errors()->add('sign_pdf', 'File trình ký '. $file_name .': không tìm thấy user ('.$user_fails->username .')' . $user_fails->name . ' trong cây duyệt!');
                        });
                        break;
                    }
                }
            }else {

                $flag_pdf = false;
                $validator->after(function ($validator) use($file_name) {
                    $validator->errors()->add('sign_pdf', 'File trình ký '. $file_name .' chưa gán người ký' );
                });
                break;
            }

        }
        return  $validator;
    }
    private function check_team_approve($user_id){
        $flag = false;
        foreach ($this->request->team_users as  $team_user) {
            if ($user_id == $team_user['user_id']) {
                $flag  = true;
            }
        }
        return  $flag ;
    }
    protected function validate_update($id)
    {
        if (!isset($this->request['amount']) ) {
            $this->request['amount'] = 0;
        }
        $this->request['budget_type'] = 1;
       $validator  = parent::validate_update($id);

        $is_sign = false;
        foreach ($this->request->team_users as  $value) {
            if ($value['user_id'] != '' && $value['step'] == '3' && $value['sign'] == '1') {
                $is_sign = true;
            }
        }
        if (!$is_sign) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('sign', 'Vui lòng chọn người phê duyệt.');
                });

        }
        $list = array();
        foreach ($this->request->team_users as  $value) {
            array_push($list ,$value['user_id']);
        }
        if ( Ultils::is_duplicate($list)   ) {
            $validator->after(function ($validator) {
                $validator->errors()->add('sign', 'Trùng user trong cây duyệt.');
            });
        }
        //
        $flag_pdf = false;
        $user_fails = new User;
       // dd($this->request->filesigns);
        foreach ($this->request->filesigns as  $filesign) {
            $signs = [];
            $file_name = $filesign['attachment_file'][0]['name'];
            if (isset( $filesign['attachment_file'][0]['signs'])) {
                $signs = $filesign['attachment_file'][0]['signs'];
            }
            if (count($signs) > 0) {

                foreach ($signs as $sign) {

                    $flag_pdf =   $this->check_team_approve($sign['sign']);
                   // dd("test");
                    if (!$flag_pdf && auth()->user()->id != $sign['sign'] ) {
                        $user_fails  = User::find($sign['sign']);
                        $validator->after(function ($validator) use($user_fails,$file_name) {
                            $validator->errors()->add('sign_pdf', 'File trình ký '. $file_name .': không tìm thấy user ('.$user_fails->username .')' . $user_fails->name . ' trong cây duyệt!');
                        });
                        break;
                    }
                }



            }else {

                $flag_pdf = false;
                $validator->after(function ($validator) use($file_name) {
                    $validator->errors()->add('sign_pdf', 'File trình ký '. $file_name .' chưa gán người ký' );
                });
                break;
            }

        }


       return  $validator;
    }

}
?>
