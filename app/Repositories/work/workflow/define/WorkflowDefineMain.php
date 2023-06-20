<?php
namespace App\Repositories\work\workflow\define;

use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class WorkflowDefineMain {
    public static function create(Request $request)
    {
        $obj = new WorkflowDefineBase($request);
        return  $obj;
    }
}
?>
