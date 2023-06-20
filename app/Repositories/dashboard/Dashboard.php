<?php
namespace App\Repositories\dashboard;

use App\Models\document\Document;
use App\Models\payment\Payrequest;

use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

 class Dashboard{



    public function getinfo()
    {

        $result = array();

        $result['success'] = "1";
        $result['payrequest'] = array();
        $result['document'] = array();


        $result['payrequest'] = $this->getPayrequestIno();
        $result['document'] = $this->getDocumentIno();

        return $result;
    }
    public function getPayrequestIno(){

        $result = array();

        $result['success'] = "1";

        $total = Payrequest::all(['id'])->count();
        $taomoi = Payrequest::WhereNull('status')->count();
        $chuaduyet = Payrequest::Where('status','1')->count();
        $daduyet = Payrequest::where('status','2')->count();
        $dathanhtoan = Payrequest::where('status','3')->count();
        $dahuy = Payrequest::where('status','-1')->count();

        $result['total'] = $total;
        $result['taomoi'] = $taomoi;
        $result['chuaduyet'] = $chuaduyet ;
        $result['daduyet'] = $daduyet ;
        $result['dathanhtoan'] = $dathanhtoan;
        $result['dahuy'] = $dahuy;
        return $result;
    }
    public function getDocumentIno(){

        $result = array();

        $result['success'] = "1";
        $total = Document::all(['id'])->count();
        $taomoi = Document::WhereNull('status')->count();
        $chuaduyet = Document::Where('status','1')->count();
        $daduyet = Document::where('status','2')->count();
        $dahuy = Document::where('status','-1')->count();

        $result['total'] = $total;
        $result['taomoi'] = $taomoi;
        $result['chuaduyet'] = $chuaduyet ;
        $result['daduyet'] = $daduyet ;
        $result['dahuy'] = $dahuy ;



        return $result;
    }

}

?>
