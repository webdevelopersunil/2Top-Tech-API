<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\API\NotificationResource;
use App\Http\Service\TimeService;
use App\Http\Service\FCMPushNotificationService;


class NotificationController extends Controller
{
    public function notificationList(Request $request){

        $currentTime    =   (new TimeService)->currentTime();
        $user           =   auth()->user();

        $user->last_notification_seen = $currentTime;
        $user->save();

        $type = isset($request->type) ? $request->type : null;

        if($type == "markas_read"){
            if(count($user->unreadNotifications) > 0 ) {
                $user->unreadNotifications->markAsRead();
            }
        }

        $page   =   1;
        $limit              =   100;

        $response['all_unread_count']   =   $user->Notifications->sortByDesc('created_at')->forPage($page,$limit);
        $response['notification_data']  =   isset($user->unreadNotifications) ? $user->unreadNotifications->count() : 0;

        // $items = NotificationResource::collection($notifications);

        return comman_custom_response($response);
    }

    public function testNotifications(){        
        $response  = (new FCMPushNotificationService)->testNotification(Auth::user()->id);
        $message        =   __('messages.test_push_notifications');
        $status_code    =   200;
        $status         =   True;

        return common_response( $message, $status, $status_code, $response);

    }
}
