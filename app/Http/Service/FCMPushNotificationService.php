<?php

namespace App\Http\Service;

use App\Models\User;
use App\Models\Company;
use App\Models\Provider;
use  App\Models\Notification;
use App\Models\AppNotification;
use Illuminate\Support\Facades\Auth;

class FCMPushNotificationService
{
    public function sendNotificationFCM($user_id,$data){

        $user = User::where('id', $user_id)->with('UserRole')->first();
        if($user && $user->UserRole && $user->UserRole->roleDetail && $user->UserRole->roleDetail->name=== 'restaurant'){

            $accesstoken = "key=".env('RESTAURANT_FCM_KEY');
        }elseif($user && $user->UserRole && $user->UserRole->roleDetail && $user->UserRole->roleDetail->name=== 'provider'){

             $accesstoken = "key=".env('TECHNICIAN_FCM_KEY');
        }

        $post_data = '{
            "to":"'.$user->fcm_token.'",
            "data" : {
                "body" : "'.$data['message'].'",
                "title" : "' . $data['title'] . '",
                "type" : "' . $data['type'] . '",
                "id" :"' . $user->id . '",
                "message" : "'.$data['message'].'"
            },
            "notification" : {
                "body" : "' . $data['message'] . '",
                "title" : "' . $data['title'] . '",
                "type" : "' . $data['type'] . '",
                "id" : "' . $user->id . '",
                "message" : "' . $data['message'] . '",
                "icon" : "ic_launcher",
                "sound" : "default"
            },
        }';

        $header = array();
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: ' . $accesstoken;

        $curl = curl_init();
        $URL = 'https://fcm.googleapis.com/fcm/send';
        curl_setopt_array($curl, array(
          CURLOPT_URL => $URL,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$post_data,
          CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $notification = Notification::create(
            array(
                'type' => $data['type'],
                'title' => $data['title'],
                'notifiable_type'=> 'App\Models\User',
                'notifiable_id'=>$user->id,
                'status'=>json_decode($response)->success,
                'description'=>$data['message']
            )
        );
    }

    public function getIndexNumber(){
        return Notification::orderBy('id','DESC')->first('id');
    }

    public function addJob($user_id){

        $notification_data = [
            'title' => 'New Job Post',
            // 'type' => 'email',
            'message' => "A new job post has been created.",
            "ios_badgeType"=>"Increase",
            "ios_badgeCount"=> 1,
            "notification_id"=> $this->getIndexNumber(),
            "type"=>"New_job_post",
        ];
      $this->sendNotificationFCM($user_id, $notification_data);
    }


    public function editJob($user_id, $job_detail){

        $notification_data = [
            'title' => 'Job Updated by RO',
            // 'type' => 'email',
            'message' => "Job Updated. The Job ".  $job_detail." has been modified by the Restaurant.",
            "ios_badgeType"=>"Increase",
            "ios_badgeCount"=> 1,
            "notification_id"=> $this->getIndexNumber(),
            "type"=>"update_job",
        ];
        $this->sendNotificationFCM($user_id, $notification_data);
    }

    public function newJobOffer($user_id, $job_detail){

        $notification_data = [
            'title' => 'New job offer',
            // 'type' => 'email',
            'message' => "New job offer. The Job ". $job_detail ." you had applied for, has been accepted.",
            "ios_badgeType"=>"Increase",
            "ios_badgeCount"=> 1,
            "notification_id"=> $this->getIndexNumber(),
            "type"=>"new_job_offer",
        ];
        $this->sendNotificationFCM($user_id, $notification_data);
    }

    public function applyJob($user,$company_user_id){

        $notification_data = [
          'title' => 'New Job Application',
        //   'type' => 'email',
          'message' => "New Job Application. A new Technician ". $user->first_name ." has applied for Job.",
          "ios_badgeType"=>"Increase",
          "ios_badgeCount"=> 1,
          "notification_id"=> $this->getIndexNumber(),
            "type"=>"new_job_application",
        ];
        $this->sendNotificationFCM($company_user_id, $notification_data);
    }

    public function applicationAccept($user_id, $status){

        $application_status  = ($status =='Offer_Accepted') ? "accepted" : 'declined';
        $notification_data = [
          'title' => 'Job Offer '.ucfirst($application_status),
        //   'type' => 'email',
          'message' => "The Job offer has been ". $application_status ." by the technician.",
          "ios_badgeType"=>"Increase",
          "ios_badgeCount"=> 1,
          "notification_id"=> $this->getIndexNumber(),
          "type"=>"job_application_accept",
        ];
        $this->sendNotificationFCM($user_id, $notification_data);
    }

    public function changeProviderStatus($user_id, $status){

        $notification_data = [
          'title' => 'Account '. $status,
        //   'type' => 'email',
          'message' => "Admin has ".$status." the account.",
          "ios_badgeType"=>"Increase",
          "ios_badgeCount"=> 1,
          "notification_id"=> $this->getIndexNumber(),
          "type"=>"change_provider_status",
        ];
        $this->sendNotificationFCM($user_id, $notification_data);
    }

    public function welcomeUser($user){

        $notification_data = [
          'title' => 'Email Verification',
        //   'type' => 'email',
          'message' => "Welcome ". $user->first_name."! Welcome to the 2Top Tech team.",
          "ios_badgeType"=>"Increase",
          "ios_badgeCount"=> 1,
          "notification_id"=> $this->getIndexNumber(),
          "type"=>"welcome_user",
        ];
        $this->sendNotificationFCM($user->id, $notification_data);
    }

    public function testNotification(){

        $notification_data = [
            'title' => 'test title',
            // 'type' => 'email',
            'message' => "This is a test notification content.",
            "ios_badgeType"=>"Increase",
            "ios_badgeCount"=> 1,
            "notification_id"=> $this->getIndexNumber(),
            "type"=>"test_notification",
        ];

        $this->sendNotificationFCM(Auth::user()->id, $notification_data);
    }

    public function sendStartTrackingNotification($company_user_id,$job){

        $notification_data = [
            'title' => 'Job Started',
            // 'type' => 'email',
            'message' => "Job Started, The tech working on Job ($job->description)  the timer has started for the Job",
            "ios_badgeType"=>"Increase",
            "ios_badgeCount"=> 1,
            "notification_id"=> $this->getIndexNumber(),
            "type"=>"start_tracking_notification",
        ];
        $this->sendNotificationFCM($company_user_id, $notification_data);
    }

    public function technicainSubmittedInvoice($job,$company_user_id){

        $notification_data = [
            'title' => 'Technicain Submitted Invoice',
            // 'type' => 'email',
            'message' => "Technicain Submitted Invoice, Your invoice for Job Service ( ".$job->service->name." ) has been submitted by the Technician.",
            "ios_badgeType"=>"Increase",
            "ios_badgeCount"=> 1,
            "notification_id"=> $this->getIndexNumber(),
            "type"=>"technician_submit_invoice",
        ];
        $this->sendNotificationFCM($company_user_id, $notification_data);
    }

    public function restaurantApprovedInvoice($user_id,$invoice,$job){

        $notification_data = [
            'title' => 'Business owner approved invoice',
            // 'type' => 'email',
            'message' => "Your bill for Job ($job->description) submitted on ( ".$invoice->updated_at." ) has been approved by the business owner.",
            "ios_badgeType"=>"Increase",
            "ios_badgeCount"=> 1,
            "notification_id"=> $this->getIndexNumber(),
            "type"=>"restaurant_approved_invoice",
        ];
        $this->sendNotificationFCM($user_id, $notification_data);
    }

    public function bookingCancellationEmailToTechnician($provider_id, $company_id){

        $provider   =   Provider::with('user')->find($provider_id);
        $company    =   Company::with('user')->find($company_id);

        $notification_data = [
            'title' => $company->business_name.' cancelled booking.',
            // 'type' => 'email',
            'message' => 'Your booking with '.$company->business_name.' is cancelled',
            "ios_badgeType"=>"Increase",
            "ios_badgeCount"=> 1,
            "notification_id"=> $this->getIndexNumber(),
            "type"=>"restaurant_cancelled_booking",
        ];

        $this->sendNotificationFCM($provider->id, $notification_data);
    }
}
