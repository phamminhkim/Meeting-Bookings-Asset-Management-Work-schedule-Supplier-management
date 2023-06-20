<?php

namespace App\Mail;

use App\Notifications\NotiBaseModel;
use App\User;
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailUserRegisterSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $data =null;
    public $user = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NotiBaseModel $data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user  = User::find($this->user->id);
        $company = Company::find($this->user->company_id);
        $department = Department::find($this->user->department_id);

        return $this->subject($this->data->title_prefix.$this->data->title)
        ->markdown('email.user_registered_success', ['user' => $user, 'company'=>$company, 'department'=>$department, 'password'=>$this->data->password]);
    }
}
