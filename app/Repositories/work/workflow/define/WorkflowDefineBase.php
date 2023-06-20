<?php
namespace App\Repositories\work\workflow\define;

use App\Models\shared\DocumentType;
use App\Models\shared\Group;
use App\Models\shared\Team;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\User;

class WorkflowDefineBase extends WorkflowDefineAbs{

    public  function getLayout(){
        $this->layout = new HtmlAtrtibute();
        return $this->layout;
    }

}
?>
