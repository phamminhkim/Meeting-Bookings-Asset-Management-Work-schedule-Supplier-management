<?php 
namespace App\Repositories\managerprice;

use App\Repositories\HtmlAtrtibute;

class PriceAppReqBase extends PriceAppReqAbs{
     public  function getLayout(){

        $this->layout = new HtmlAtrtibute();
        
         return $this->layout;

     }
}



?>