<?php
namespace App\Repositories\reminder;

use App\Models\shared\Reminder;

trait HasReminder {


        //Chỉ lấy 1 những nhắc nhở của user login
        public function reminder_one(){
            return $this->morphOne(Reminder::class,'reminderable')->where('user_id',auth()->user()->id);
        }

        public function reminder(){
            return $this->morphMany(Reminder::class,'reminderable');
        }



}
?>
