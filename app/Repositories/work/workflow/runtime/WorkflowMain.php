<?php
namespace  App\Repositories\work\workflow\runtime;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
class WorkflowMain
{
    public static function create(Request $request)
    {

        $obj = new WorkflowBase($request);
        return  $obj;
    }
}
