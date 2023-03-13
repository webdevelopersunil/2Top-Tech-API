<?php

namespace App\Http\Service;

use App\Models\User;
use App\Models\Company;
use App\Models\Provider;
use App\Jobs\SendMailJob;
use App\Mail\RoSendOffer;
use App\Models\RestaurantJob;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Service\FCMPushNotificationService;

class PushNotificationService
{

    public static function dispatch($data){
        dispatch(new SendMailJob($data));
    }

    public function getEmailSubject($template,$data){

        $emailTemplate              =   get_email_template($template);
        $emailSub['email_body']     =   getFormattedEmailData($data, $emailTemplate->email_body);
        $emailSub['subject']        =   getFormattedEmailData($data, $emailTemplate->email_subject);

        return $emailSub;
    }

    public function sendEmailNotification($data){
        $requestType = $data['requestType'];
        $job =   RestaurantJob::where('id', $data['job_id'])->with('company','service')->first();
        //send job Post notify to Restaurant
        $elements = [
            '{{RO}}'       =>  $job->company->business_name,
            '{{serviceName}}'  =>  $job->service->name,
            '{{jobName}}'  =>  $job->description
        ];

        $response    =    $this->getEmailSubject('job_post_notify',$elements);
        $emailElements = array(
            'email_body'    =>  $response['email_body'],
            'subject'       =>  $response['subject'],
            'toMail'        =>  Auth::user()->email
        );
        if($requestType == 'newPost'){
            $this->dispatch($emailElements);
            // This notification is commented which is sending RO itself
            // (new FCMPushNotificationService)->addJob(Auth::user()->id);
        }


        if (count($data['providers']) > 0) {

            $job_id = $data['job_id'];

            foreach ($data['providers'] as $provider) {

                $provider_user_details = User::where('id', $provider->user_id)->first();

                // $provider->email;
                // $provider->user_id;
                // $provider->provider_id;
                // $provider->fcm_token;
                // $provider->preferred_distance;
                // $provider->distance;

                $plainData = [
                    '{{providerName}}'        =>  Auth::user()->first_name.' '.Auth::user()->last_name,
                    '{{serviceName}}'         =>  $job->service->name
                ];
                if($requestType == 'newPost'){
                    $response           =   $this->getEmailSubject('new_job_post',$plainData);
                }else if($requestType == 'updatePost'){
                    $response           =   $this->getEmailSubject('job_post_update_by_ro', $plainData);
                }
                $data = array(
                    'email_body'    =>  $response['email_body'],
                    'subject'       =>  $response['subject'],
                    'toMail'        =>  $provider->email
                );
                $this->dispatch($data);

                if($requestType == 'newPost'){
                   (new FCMPushNotificationService)->addJob($provider_user_details->id);
                }else if($requestType == 'updatePost'){
                    (new FCMPushNotificationService)->editJob($provider_user_details->id, $job->description );
                }
            }
        }
    }

    public function restaurantSendJobOfferToProvider($provider,$company,$jobApplicationDetail){

        $details = [
            'toEmail'           =>  $provider->user->email,
            'name'              =>$provider->user->first_name.' '.$provider->user->last_name,
            'template'          =>'send_offer_to_provider',
            'restaurant_name'   =>$company->business_name,
            'job_name'          =>$jobApplicationDetail->job->description,
            'service_name'      =>$jobApplicationDetail->job->service->name,
        ];

        Mail::queue(new RoSendOffer($details));
        // Mail::to($details['toEmail'])->send(new RoSendOffer($details));

        return True;
    }

    public function providerapplyingJob($email,$user, $job_id){

        $restaurant =   User::where('email',$email)->first('display_name');
        $job        =   RestaurantJob::where('id', $job_id)->first('description');

        $details = [
            '{{ro}}'           =>  $restaurant->display_name,
            '{{technician}}'   =>  $user->first_name.' '.$user->last_name,
            '{{job}}'          =>  $job->description
        ];

        $response           =    $this->getEmailSubject('provider_applying_job', $details);
        $emailElements      = array(
            'email_body'    =>  $response['email_body'],
            'subject'       =>  $response['subject'],
            'toMail'        =>  $email
        );

        $this->dispatch($emailElements);

        return True;
    }

    public function partRequestMailToAdmin($partRequest,$provider){

        $data = [
            '{{admin}}'        =>  'AdminName',
            '{{name}}'         =>  Auth::user()->first_name.' '.Auth::user()->last_name,
            '{{parts_detail}}' =>  '',
        ];

        $emailTemplate = get_email_template('part_request_email');
        $email_body = getFormattedEmailData($data, $emailTemplate->email_body);
        $subject = getFormattedEmailData($data, $emailTemplate->email_subject);

        $data = array(
            'email_body'    =>  $email_body,
            'subject'       =>  $subject,
            'toMail'        =>  env('ADMIN_EMAIL')
        );

        $this->dispatch($data);
    }

    public function bankInfoUpdatedMailTOAdmin($providerBankInfo){

        $provider_id    = $providerBankInfo->provider_id;
        $uuid           = $providerBankInfo->uuid;

        $data = [
            '{{admin}}'        =>  'AdminName',
            '{{name}}'         =>  Auth::user()->first_name.' '.Auth::user()->last_name,
            '{{parts_detail}}' =>  '',
        ];

        $toMail = env('ADMIN_EMAIL');

        $emailTemplate  = get_email_template('bank_detail_updated');
        $email_body     = getFormattedEmailData($data, $emailTemplate->email_body);
        $subject        = getFormattedEmailData($data, $emailTemplate->email_subject);

        $data = array(
            'email_body'=>$email_body,
            'subject'   =>$subject,
            'toMail'    =>$toMail
        );

        $this->dispatch($data);

    }

    public function jobCompletionEmailNotificationTechnician($job,$user,$company){

        $data = [
            '{{RO}}'            =>  $company->business_name,
            '{{job}}'           =>  $job->description,
            '{{technicianName}}'=>  $user->first_name.' '.$user->last_name,
            '{{serviceName}}'   =>  $job->service->name,
        ];

        $toMail         = $company->user->email;
        $emailTemplate  = get_email_template('Job_completion_booking_invoice_provider');
        $email_body     = getFormattedEmailData($data, $emailTemplate->email_body);
        $subject        = getFormattedEmailData($data, $emailTemplate->email_subject);

        $data           = array(
                            'email_body'    =>  $email_body,
                            'subject'       =>  $subject,
                            'toMail'        =>  $toMail
                        );

        $this->dispatch($data);
    }

    public function jobCompletionEmailNotification($user,$invoice){

        $company    =   (new Company)->company($user->id);

        $data = [
            '{{restaurant}}'    =>  $company->business_name,
            '{{job}}'           =>  'jobbbb',
            '{{amount}}'        =>  $invoice->total_amount,
        ];

        $toMail         = $user->email;
        $emailTemplate  = get_email_template('Job_completion_approve_and_payment');
        $email_body     = getFormattedEmailData($data, $emailTemplate->email_body);
        $subject        = getFormattedEmailData($data, $emailTemplate->email_subject);

        $data           = array(
                            'email_body'=>$email_body,
                            'subject'   =>$subject,
                            'toMail'    =>$toMail
                        );

        $this->dispatch($data);
    }

    public function sendMailJobOfferAcceptedReject($status, $details){

        $emailTemplate          =   $status=='Offer_Accepted' ? "Job_Offer_Accepted_from_RO" : 'Job_Offer_Rejected_from_RO';

        $data = [
            '{{restaurant}}'    =>  $details['restaurant_name'],
            '{{job_id}}'        =>  $details['service_name'],
            '{{tech_name}}'     =>  $details['name'],
        ];

        $toMail         = $details['toEmail'];
        $emailTemplate  = get_email_template($emailTemplate);
        $email_body     = getFormattedEmailData($data, $emailTemplate->email_body);
        $subject        = getFormattedEmailData($data, $emailTemplate->email_subject);

        $data           = array(
                            'email_body'=>$email_body,
                            'subject'   =>$subject,
                            'toMail'    =>$toMail
                        );

        $this->dispatch($data);

    }

    public function bookingCancellationEmailToTechnician($provider_id, $company_id){

        $provider    =   Provider::with('user')->find($provider_id);
        $company    =   Company::with('user')->find($company_id);

        $data = [
            '{{provider_name}}'     =>  $provider->user->first_name.' '.$provider->user->last_name,
            '{{company_name}}'      =>  $company->business_name
        ];

        $toMail         =   $provider->user->email;
        $emailTemplate  =   get_email_template('Cancellation_job_email_notification_to_technician');
        $email_body     =   getFormattedEmailData($data, $emailTemplate->email_body);
        $subject        =   getFormattedEmailData($data, $emailTemplate->email_subject);

        $data           =   array(
                                'email_body'=>$email_body,
                                'subject'   =>$subject,
                                'toMail'    =>$toMail
                            );
        $this->dispatch($data);
    }

    public function bookingCancellationEmailToRestaurant($company_id,$provider){

        $company    =   Company::with('user')->find($company_id);

        $data = [
            '{{company_name}}'      =>  $company->business_name,
            '{{provider_name}}'     =>  $provider->first_name.' '.$provider->last_name
        ];

        $toMail         =   $company->user->email;
        $emailTemplate  =   get_email_template('Cancellation_job_email_notification_to_restaurant');
        $email_body     =   getFormattedEmailData($data, $emailTemplate->email_body);
        $subject        =   getFormattedEmailData($data, $emailTemplate->email_subject);
        $data           =   array(
                                'email_body'=>$email_body,
                                'subject'   =>$subject,
                                'toMail'    =>$toMail
                            );

        $this->dispatch($data);
    }

    public function ratingToCompany($details){

        $data = [
            '{{rate}}'      =>  $details['rate'],
            '{{RO}}'        =>  $details['company_name']
        ];

        $toMail         =   env('ADMIN_EMAIL');
        $emailTemplate  =   get_email_template('job_rating');
        $email_body     =   getFormattedEmailData($data, $emailTemplate->email_body);
        $subject        =   getFormattedEmailData($data, $emailTemplate->email_subject);
        $data           =   array(
                                'email_body'=>$email_body,
                                'subject'   =>$subject,
                                'toMail'    =>$toMail
                            );

        $this->dispatch($data);
    }
}
