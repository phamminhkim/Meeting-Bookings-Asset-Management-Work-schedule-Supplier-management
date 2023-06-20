<?php

namespace App\Models\shared;

use App\Repositories\reminder\HasReminder;
use App\Traits\HasTimeline;
use Illuminate\Database\Eloquent\Model;

class Wfmain extends Model
{
   
    protected $fillable = ['id', 'company_id','document_type_id','name','description','active','created_at','updated_at','code'];

    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function document_type(){
        return $this->belongsTo(DocumentType::class);
    }
    public function wfsteps(){
        return $this->hasMany(Wfstep::class);
    }

}
