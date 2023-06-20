<?php

namespace App\Ultils;

use Illuminate\Support\Facades\DB;

use App\Models\car\CauseMeasure;
use App\Models\car\MonitorImplementation;
use App\Models\payment\Payrequest;
use App\Models\shared\Menu;
use App\Models\shared\Position;
use App\Models\shared\PositionApprove;
use App\Models\calendar\CalendarDetail;
use App\Models\calendar\Calendar;
use App\Models\calendar\CalendarType;
use App\Models\calendar\CalendarHoliday;
use Illuminate\Support\Str;


use Carbon\Carbon;
use DateTime;
use Doctrine\Inflector\Rules\Word;
use Exception;
use PDO;
use App\Repositories\HtmlAtrtibute;
use PhpParser\Node\Stmt\TryCatch;

class Ultils
{
     public static $MODULE_PAYMENT = "PAYMENT";
     public static $MODULE_CARS = "CARS";
     public static $MODULE_ASSET = "ASSET";  
     public static $MODULE_PRICE = "PRICE";
     public static $MODULE_REPORT = "REPORT";
     public static $MODULE_WORKFLOW = "WORKFLOW";
     public static $MODULE_ISSUE = "ISSUE";
     public static $MODULE_HR = "HR";
     public static $MODULE_PAYMENT_FORMAT_SERIAL_NUMBER = 6;
     public static $MODULE_FORMAT_SERIAL_NUMBER = 6;
     public static $MODULE_PAYMENT_DNTT = "DNTT"; //Đề nghị thanh toán
     public static $MODULE_PAYMENT_DNTU = "DNTU"; //Đề nghị tạm ứng
     public static $MODULE_PAYMENT_QTTU = "QTTU"; //Quyết toán tạm ứng
     public static $MODULE_PAYMENT_CPTK = "CPTK"; //Quyết toán tạm ứng
     public static $MODULE_PAYMENT_DVNS = "DVNS"; //Duyệt vượt ngân sách
     public static $MODULE_PAYMENT_TTDB = "TTDB"; //Thanh toán đặc biệt
     public static $MODULE_WORKFLOW_YVPP = "YVPP"; //Văn phòng phẩm
     //Issue
     public static $MODULE_ISSUE_YCDV = "YCDV"; //Phê duyệt vượt ngân sách
     //car
     // public static $MODULE_CAR = "CARS";
     //public static $MODULE_PCAR = "PCARS";
     //document
     public static $MODULE_DOCUMENT_PDNS = "PDNS"; //Phê duyệt vượt ngân sách
     public static $MODULE_DOCUMENT_BGIA = "BGIA"; //Phê duyệt Báo giá
     public static $MODULE_DOCUMENT_CHHH = "CHHH"; //Phê duyệt Chi hoa hồng
     public static $MODULE_DOCUMENT_TKCH = "TKCH"; //Trình ký chung
     public static $MODULE_DOCUMENT_TKRT = "TKRT"; //Trình ký chung (riêng tư)
     //Trình duyệt giá
     public static $MODULE_DOCUMENT_DGIA = "DGIA"; //Trình duyệt giá
     //
     public static $MODULE_PAYREQUEST_MODEL = "PAYREQUEST";
     //Expand Left menu
     public static $EXPAND_LEFT_MENU = "expand_menu";

     //Url view
     public static $URL_PAYMENT_REQUEST_VIEW = "payment/requests?type=view&id=";
     public static $URL_APPROVE_REQUEST_VIEW = "approve/request?type=view&id=";

     public static $URL_DOCUMENT_VIEW = "documents?type=view&id=";
     public static $URL_ASSET_CONFIRM = "asset/my?type=confirm&id="; // Xác nhận tài sản
     public static $URL_APPROVE_DOCUMENT_VIEW = "approve/document?type=view&id=";

     public static $URL_WORKFLOW_VIEW = "works/list/$?type=view&id=";
     public static $URL_APPROVE_WORKFLOW_VIEW = "approve/works/list/$?type=view&id=";

     public static $URL_PRICE_VIEW = "price/requests?type=view&id=";

     public static $URL_ISSUE_VIEW = "issue/requests?type=view&id=";
     public static $URL_APPROVE_PRICE_VIEW = "approve/price?type=view&id=";
     public static $URL_CAR_VIEW = "car/systems?type=view&id=";
     public static $URL_APPROVE_CAR_VIEW = "approve/car/systems?type=view&id=";

     public static $URL_HRPRODUCTIVITY_VIEW = "/productivity/document?type=view&id=";
     public static $URL_APPROVE_HRPRODUCTIVITY_VIEW = "approve/productivity?type=view&id=";

     public static  $CONTRACT_TYPE = array('day' => 1, 'month' => 2, 'year' => 3);

     public static $WORKFLOW_CODE_DEFAULT = "WLDC";

     public static  $CONTROL_TYPE = array(
          'TEXT' => 1,
          'TEXTAREA' => 2,
          'NUMBER' => 3,
          'LOGIC' => 4,
          'COMBOBOX' => 5,
          'LIST' => 6,
          'DATE' => 7,
          'DATETIME' => 8,
          'USER' => 9,
          'FILE' => 10,
          'LINK' => 11,
          'APPROVE' => 12,
          'LONGTEXT' => 13,
          'TIME' => 14,
          'BOOLEAN' => 15,
          'USERS' => 16,
     );

     public static  $WORKFLOW_ICONS = array(
          'PHASEDONE' => "fas fa-check bg-success",
          'WORKFLOWCREATE' => "fas fa-file bg-success",
          'WORKFLOWDONE' => "fas fa-check-circle bg-success",
          'WORKFLOWCANCEL' => "fas fa-window-close bg-danger",
          'REPORTSUBMIT' => "fas fa-file-import bg-success",
          'REPORTUNSUBMIT' => "fas fa-file-export bg-warning",
          'JOBASSIGNUSER' => "fas fa-tasks bg-primary",
          'JOBUNASSIGNUSER' => "fas fa-tasks bg-warning",

          'REQUEST_APPROVEMENT' => "fas fa-briefcase bg-primary",
          'REQUEST_ACCEPTED' => "fas fa-check bg-success",
          'REQUEST_DENIED' => "fas fa-comments bg-danger",
          'NOTIFICATION' => "far fa-bell",
          'RESPONSE' => "fas fa-comment"
     );

     public static function getDateDueNext($currDate, $frequency, $frequency_type)
     {

          $new_date_due  = new Carbon($currDate);

          if ($frequency_type != null && $frequency != null &&  $frequency > 0) {

               if ($frequency_type == Ultils::$CONTRACT_TYPE['day']) {
                    //Ngày
                    $new_date_due->addDay($frequency);
               } elseif ($frequency_type == Ultils::$CONTRACT_TYPE['month']) {
                    $new_date_due->addMonth($frequency);
               } elseif ($frequency_type == Ultils::$CONTRACT_TYPE['year']) {
                    //năm
                    $new_date_due->addYear($frequency);
               } else {
                    //tháng
                    $new_date_due->addMonth($frequency);
               }
          }
          return  $new_date_due->format('Y-m-d');
     }

     public static  function parseJsonArray($jsonArray, $parentID = 0)
     {

          $return = array();
          foreach ($jsonArray as $key => $value) {
               $returnSubSubArray = array();
               if (isset($value->children)) {
                    $returnSubSubArray = Ultils::parseJsonArray($value->children, $value->id);
               }
               $return[] = array('id' => $value->id, 'parentID' => $parentID);
               $return = array_merge($return, $returnSubSubArray);
          }

          return $return;
     }

     public static  function recursiveDelete($id, $db)
     {
          $db_conn = $db;
          $query = $db->query("select * from tbl_menu where parent = '" . $id . "' ");
          if ($query->rowCount() > 0) {
               while ($current = $query->fetch(PDO::FETCH_ASSOC)) {
                    Ultils::recursiveDelete($current['id'], $db_conn);
               }
          }
          $db->exec("delete from tbl_menu where id = '" . $id . "' ");
     }

     public static function getMenuRoot_NestedSet($menu)
     {
          try {
               $root_menu = Menu::where('left', '<', $menu->left)->where('right', '>', $menu->right)->orderBy('left')->first();
          } catch (\Throwable $th) {
               $root_menu = array();
          }

          return $root_menu;
     }
     public static function getMenuTreeTop_NestedSet($menu)
     {
          try {
               $treetop_menus = Menu::where('left', '<=', $menu->left)->where('right', '>=', $menu->right)->get();
          } catch (\Throwable $th) {
               $treetop_menus = array();
          }

          return $treetop_menus;
     }
     public static function getMenuTreeTopWithoutMe_NestedSet($menu)
     {
          try {
               $treetop_menus = Menu::where('left', '<', $menu->left)->where('right', '>', $menu->right)->get();
          } catch (\Throwable $th) {
               $treetop_menus = array();
          }

          return $treetop_menus;
     }
     public static function getRecursiveMenuChildren($menu)
     {
          $menu->has_childrens = count($menu->childs) > 0;
          foreach ($menu->childs as $child) {
               $child = Ultils::getRecursiveMenuChildren($child);
          }

          return $menu;
     }

     public static function getBase64ImageSize($base64Image)
     { //return memory size in B, KB, MB
          try {
               $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
               $size_in_kb    = $size_in_bytes / 1024;
               $size_in_mb    = $size_in_kb / 1024;

               return $size_in_mb;
          } catch (\Exception $e) {
               return $e;
          }
     }

     public static function isDate($value)
     {
          if (!$value) return false;
          try {
               new DateTime($value);
               return true;
          } catch (Exception $e) {
               return false;
          }
     }

     public static function convert_number_to_words($number)
     {
          $hyphen = ' '; //gạch nối
          $conjunction = ' lẻ '; //kết hợp
          $separator = ' '; //phân tách
          $negative = 'âm '; //
          $decimal = ' phẩy '; //

          $dictionary = array(
               0 => 'không',
               1 => 'một',
               2 => 'hai',
               3 => 'ba',
               4 => 'bốn',
               5 => 'năm',
               6 => 'sáu',
               7 => 'bảy',
               8 => 'tám',
               9 => 'chín',
               10 => 'mười',
               11 => 'mười một',
               12 => 'mười hai',
               13 => 'mười ba',
               14 => 'mười bốn',
               15 => 'mười lăm',
               16 => 'mười sáu',
               17 => 'mười bảy',
               18 => 'mười tám',
               19 => 'mười chín',
               20 => 'hai mươi',
               30 => 'ba mươi',
               40 => 'bốn mươi',
               50 => 'năm mươi',
               60 => 'sáu mươi',
               70 => 'bảy mươi',
               80 => 'tám mươi',
               90 => 'chín mươi',
               100 => 'trăm',
               1000 => 'nghìn',
               1000000 => 'triệu',
               1000000000 => 'tỷ',
               1000000000000 => 'nghìn tỷ',
               1000000000000000 => 'triệu tỷ',
               1000000000000000000 => 'tỷ tỷ',
          );
          if (!is_numeric($number)) {
               return false;
          }

          if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
               // overflow
               trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                    E_USER_WARNING
               );
               return false;
          }
          if ($number < 0) {
               return $negative . Ultils::convert_number_to_words(abs($number));
          }
          $string = $fraction = null;
          if (strpos($number, '.')) {
               list($number, $fraction) = explode('.', $number);
          }
          switch (true) {
               case $number < 21:
                    $string = $dictionary[$number];

                    break;
               case $number < 100:
                    $tens   = ((int) ($number / 10)) * 10;
                    $units  = $number % 10;
                    $string = $dictionary[$tens];
                    if ($units) {
                         $string .= $hyphen . $dictionary[$units];
                    }
                    // dd( $string );
                    break;
               case $number < 1000:
                    $hundreds  = $number / 100;
                    $remainder = $number % 100;
                    $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                    if ($remainder) {
                         $string .= $conjunction . Ultils::convert_number_to_words($remainder);
                    }
                    // dd( $remainder );
                    break;
               default:
                    $baseUnit = pow(1000, floor(log($number, 1000)));
                    $numBaseUnits = (int) ($number / $baseUnit);
                    $remainder = $number % $baseUnit;
                    $string = Ultils::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                    //dd( $baseUnit );
                    if ($remainder) {
                         $string .= $remainder < 100 ? $conjunction : $separator;
                         $string .= Ultils::convert_number_to_words($remainder);
                    }

                    break;
          }
          if ($fraction != null  && is_numeric($fraction)) {
               $string .= $decimal;
               $words = array();
               foreach (str_split((string)$fraction) as $number) {
                    $words[] = $dictionary[$number];
               }
               $string  .= implode(' ', $words);
          }

          return $string;
     }
     public static function is_weekend($year, $month, $day)
     {
          $time = mktime(0, 0, 0, $month, $day, $year);
          $weekday = date('w', $time);
          return ($weekday == 0 || $weekday == 6);
     }
     public static function is_position($user_id)
     {
          $info_position =  PositionApprove::where('user_id', $user_id)->where('active', 1)->get();
          foreach ($info_position as $position)
               $posi = Position::find($position->position_id);
          return $posi->name;
     }
     public static function is_cause_measure($cause_measure_id)
     {
          $info_cause_measure =  CauseMeasure::find($cause_measure_id);
          return $info_cause_measure->measure;
     }
     public static function is_duplicate($array)
     {

          $dup_array  = array();
          foreach ($array as $value) {
               if (in_array($value, $dup_array)) {
                    return true;
               } else {
                    array_push($dup_array, $value);
               }
          }
          return false;
     }

     public static function name_to_unique_name($name)
     {
          return str_replace(' ', '_', preg_replace('/[^a-zA-Z0-9 ]/', '', strtolower(Ultils::remove_vietnamese_cast($name))));
     }
     public static function remove_vietnamese_cast($str)
     {
          $unicode = array(
               'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
               'd' => 'đ',
               'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
               'i' => 'í|ì|ỉ|ĩ|ị',
               'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
               'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
               'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
               'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
               'D' => 'Đ',
               'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
               'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
               'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
               'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
               'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
          );

          foreach ($unicode as $nonUnicode => $uni) {
               $str = preg_replace("/($uni)/i", $nonUnicode, $str);
          }
          return $str;
     }

  
     public static function check_ngaylam($codecompany, $codetype, $year, $month, $day, $songay)
     { 
          $data = [];
         

      
          try { 
              
               $data['mess']="";
               $data['check_ngaylam'] = false;

             
               $index=0;
               while (true) {
                    $cal_type =CalendarType::where('code',$codetype)->first();
                    $c =  Calendar::where('company_id', $codecompany)->where('calendar_type_id', $cal_type->id)->where('year',$year)->first();    

                    $input = Carbon::createFromDate($year,$month,$day+1);
                    $input2 = Carbon::createFromDate($year,$month,$day)->format('m');
                    $input3 = Carbon::createFromDate($year,$month,$day)->format('d');
                
                    $inputfms = Carbon::createFromDate($year,$input2,$input3)->format('m');
                    $inputfmy = Carbon::createFromDate($year,$input2,$input3)->format('Y');
                    $inputfma = Carbon::createFromDate($year,$input2,$input3);
                    $inputfmmm = Carbon::createFromDate($year,$input2,$input3)->format('d');

                    if($input->weekOfYear>=10){
                         $week = $year ."-W". $input->weekOfYear;

                    }else{
                         $week = $year ."-W0". $input->weekOfYear;

                    }
                    $detail = CalendarDetail::where('calendar_id',$c->id)
                                   ->where('month',$year.'-'.$inputfms)
                                   ->where('week',$week)->first(); 
                                   $index++;
                         if($detail != null && $detail->work!=null  && $detail->work[$inputfma->dayOfWeek]==null){
                             $songay++;
                         }
                        
                        else if($index==$songay ){
                              $data['check_ngaylam'] = true;
                              $data['mess'] = "$inputfmmm-$inputfms-$inputfmy";
                             

                         }

                    $day++;    

          }

     } catch (\Throwable $e) {
     //  $data['data']['errors']  =  $e->getMessage();

     
         
          

         

         }     
    
         return $data;
    
     }
     public static function check_workday($codecompany, $codetype, $year, $month, $day)
     {
          $data = [];
          $data['workday'] = false;
          $data['mess']="";
         
          $cal_type =CalendarType::where('code',$codetype)->first();
           
          $c =  Calendar::where('company_id', $codecompany)->where('calendar_type_id', $cal_type->id)->where('year',$year)->first();
        
          $input = Carbon::createFromDate($year,$month,$day+1);

          $inputfm = Carbon::createFromDate($year,$month,$day);
           
          if($input->weekOfYear>=10){
               $week = $year ."-W". $input->weekOfYear;

          }else{
               $week = $year ."-W0". $input->weekOfYear;

          }
          // $week = $year ."-W". $input->weekOfYear;
          $detail = CalendarDetail::where('calendar_id',$c->id)
          ->where('month',$year.'-'.$month)
          ->where('week',$week)->first();
         
          $detailholiday= CalendarDetail::where('calendar_id',$c->id)->where('month',$year.'-'.$month)
          ->pluck('calendar_holiday_id')->toArray();

          $detailwork= CalendarDetail::where('calendar_id',$c->id)->where('month',$year.'-'.$month)
          ->pluck('work')->toArray();
          // dd($c);
          if($detail==null){
               //dd("Ngày $day-$month-$year chưa tạo");
               $data['mess'] = "Ngày $day-$month-$year chưa tạo";
          }
        
          if($detail !=null && $detail->work=!null && $detail->work[$inputfm->dayOfWeek]==null ){
              
           foreach ($detailwork as $dayw) {
               foreach ($detailholiday as $dayh) {
               for ( $i=0; $i <7 ; $i++) {
                    if($dayw[$inputfm->dayOfWeek]==null){
                         $holidayn=$dayh[$inputfm->dayOfWeek];
                         $holidayname= CalendarHoliday::where('id','=',$holidayn)->pluck('name')->toArray();
                         foreach ($holidayname as $rr) {
                         //dd("Ngày nghỉ với lí do: ".$dayh[$inputfm->dayOfWeek].'.'.$rr);
                         $data['mess'] = "Ngày nghỉ với lí do: ".$dayh[$inputfm->dayOfWeek].'.'.$rr;
                        
                    
                    }
                    }         
                    }
               }       
           }
         
          }
         else{

               if ($detail == null) {
                    $data['workday'] = false;
               }else{
                    $data['workday'] = true;
                    $data['mess']="Ngay co di lam";
               }
          
         }
        
         return $data;
     }
}
