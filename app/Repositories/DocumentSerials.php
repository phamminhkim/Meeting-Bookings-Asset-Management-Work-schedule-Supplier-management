<?php

namespace App\Repositories;

use App\Models\asset\AssetUniqueId;
use App\Models\shared\UniqueId;
use Dotenv\Validator;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use JsonException;

class DocumentSerials
{

    /**
     * Hàm cấp số serial cho các chứng từ
     */
    public static function getSerial($module, $document_type_code,$company_id,$format=6,$flag=false)
    {
        if($format == ""){
            $format = 6;
        }
        $serial = "";
        try {

           //lấy số và lock item
            $uniq = UniqueId::where('module', $module)->where('document_type_code', $document_type_code)->where('company_id',$company_id)->sharedLock()->get()->first();

            if ($uniq) {
                if($flag){

                   /// dd($uniq->auto );
                    if ($uniq->auto == '1') {
                        //kiểm tra và reset số chứng từ
                        if ($uniq->year != date("Y")) {
                            $uniq->serial = '000001';
                            $uniq->year = date("Y");
                            $serial = substr($uniq->year,2,2). $uniq->letter . '' . $uniq->serial;
                        }else {
                            $serial = substr($uniq->year,2,2). $uniq->letter . '' . $uniq->serial;
                        }
                    }else {
                        //dành cho code cũ đang chạy
                        $serial =  $uniq->document_type_code . '' . $uniq->serial;
                    }
                }else{
                    $serial =  $uniq->serial;
                }
                //hệ thống sẽ thực

                $uniq->serial = $uniq->serial + 1 ;
                $uniq->serial = str_pad($uniq->serial, $format, '0', STR_PAD_LEFT);
                //dd( $uniq );
                $uniq->save();

            }else{
                throw new Exception( "Không cấp phát được serial number" , 1);
            }

        } catch (\Exception $e) {

           throw new Exception( "Không cấp phát được serial number" , 1);

        }
        return $serial;
    }
   


}
