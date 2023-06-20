<?php

namespace App\Models\service;

use Illuminate\Database\Eloquent\Model;

use App\Models\shared\Comment;
use App\Models\shared\Company;
use App\Models\shared\File;
use App\Models\shared\Team;
use App\Models\shared\ReviewRating;

use App\Models\shared\UserTeam;
use App\Models\service\ServiceCategory;
use App\Models\service\TicketAssign;

use App\User;
class Ticket extends Model
{
  
    /**
     * Get the ticket's comment
     */
    public function comments(){
        return $this->morphMany(Comment::class,'commentable');
    }
    public function files(){
        return $this->morphMany(File::class,'fileable');
    }
    public function category(){
        return $this->belongsTo(ServiceCategory::class,'service_category_id');
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function createBy(){
        return $this->belongsTo(User::class,'create_by');
    }
    public function requestBy(){
        return $this->belongsTo(User::class,'request_by');
    }
    public function team(){
        return $this->belongsTo(Team::class);
    }
    public function reviewrating(){
        return $this->morphMany(ReviewRating::class,'objectable');
    }
    public function assign_to(){
       // return $this->belongsTo(User::class,'request_by');
       return $this->belongsTo(TicketAssign::class,'assign_to');
    }
	 public function userteam(){
        return $this->belongsTo(UserTeam::class,'team_id');
    }
    // public function reports(){
    //     return $this->belongsTo(TicketReport::class,'ticket');
    // }
	protected $fillable = ['id', 'title', 'content', 'note', 'service_category_id', 'request_date', 'finish_date', 'company_id', 'project_id', 'finished_at', 'create_by', 'request_by', 'team_id'];
}
