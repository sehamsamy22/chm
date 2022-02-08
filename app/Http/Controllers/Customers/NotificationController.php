<?php

namespace App\Http\Controllers\Customers;

use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function notifications(){

        $notifications = auth()->user()->notifications()->get();
        $notifications->markAsRead();
      return $this->apiResponse(NotificationResource::collection($notifications));
    }

//    public function notificationCount(){
//        $notificationsCount = auth('api')->user()->unreadNotifications()->count();
//        return response()->json(['status' => true,'notificationsCount' => $notificationsCount,]);
//    }

}
