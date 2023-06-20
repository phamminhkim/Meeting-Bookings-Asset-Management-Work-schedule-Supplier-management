<?php

namespace App\Http\Controllers\frontend\notification;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends BaseController
{
    public function index(Request $request)
    {
       
        return view('notification.index');
      
    }
    // public function mark_read(Request $request)
    // {
    //     $notificationId = $request->notification_id;

    //     $userUnreadNotification = auth()->user()
    //         ->unreadNotifications
    //         ->where('id', $notificationId)
    //         ->first();

    //     if ($userUnreadNotification) {
    //         $userUnreadNotification->markAsRead();
    //     }

    //     return redirect($request->url);
        
    // }
}
