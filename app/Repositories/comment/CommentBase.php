<?php 
namespace App\Repositories\comment;

use App\Models\payment\Payrequest;
use App\Models\shared\Comment;
use App\User;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class CommentBase extends CommentAbs{

    protected function update_sub_data($obj){}
    protected function store_sub_data($obj){}
    protected function destroy_sub_data($obj){}

}
?>