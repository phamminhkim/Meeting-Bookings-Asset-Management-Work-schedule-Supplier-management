<?php 
namespace App\Repositories\commentvote;

use App\Models\shared\CommentVote;
use App\User;
use Illuminate\Support\Facades\DB;
 
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
class CommentVoteBase extends CommentVoteAbs{

    protected function update_sub_data($obj){}
    protected function store_sub_data($obj){}
    protected function destroy_sub_data($obj){}

}
?>