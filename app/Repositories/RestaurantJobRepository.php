<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\JobFile;
use App\Models\Provider;
use App\Models\Equipment;
use App\Models\InvoiceItem;
use Illuminate\Support\Str;
use App\Models\RestaurantJob;
use App\Models\JobApplication;
use App\Http\Service\GoogleMap;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Service\PushNotificationService;
use App\Http\Controllers\API\StripeController;
use App\Http\Service\FCMPushNotificationService;
use App\Http\Service\TimeService;
use App\Models\JobBooking;

class RestaurantJobRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'address'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RestaurantJob::class;
    }


    public function updateJob($request){

        $data           =   $request->except(['files_id']);

        $company_id     = Company::where('user_id',Auth::user()->id)->first('id');

        $data['company_id'] = $company_id->id;

        $validJob   =   RestaurantJob::where('uuid', $request->uuid)
                        ->where('status', 'Pending')
                        ->where(function ($query) {
                            $query->doesntHave('applications')
                                ->orWhereHas('applications', function ($q) {
                                    $q->whereNotIn('application_status', ['Offer_Sent', 'Offer_Accepted']);
                                });
                        })->first();

        if($validJob){
            unset($data['service_id']);
            $validJob   =   $validJob->update($data);
            $job        =   RestaurantJob::where('uuid', $request->uuid)->first();

            // replace all old files with new files
            if($request->has('files_id')){
                (new JobFile)->saveJobFiles($request->files_id, $job->id);
            }

            // add new equipments along with existing equipments
            if($request->has('equipments_id')){
                (new Equipment)->saveJobEquipments($request->equipments_id, $job->id);
            }

            // Fetch Providers in Radius
            $response  = [];
            $response = (new GoogleMap)->fetchInRadiusRecords($data['latitude'], $data['longitude'],$job->service_id,$job->id);

            $response['requestType'] = "updatePost";
            (new PushNotificationService)->sendEmailNotification($response);

            return array('message'=>__('messages.job_updated'),'status'=>True,'statusCode'=>200,'data'=>['uuid'=>$job->uuid]);

        }else{

            return array('message'=>__('job can not be edited.'),'status'=>False,'statusCode'=>400,'data'=>[]);
        }
    }

    public function sendOffer($request){

        $user           =   Auth::user();
        $company        =   (new Company)->company($user->id);

        $jobApplication =   JobApplication::where('uuid',$request->application_id)->with('job')->first();

        if(empty($jobApplication) || $jobApplication == Null){

            return array('message'=>__('messages.application_id_does_not_exist'),'status'=>False,'statusCode'=>404,'data'=>[]);
        }

        $provider       =   Provider::where('id',$jobApplication->provider_id)->with('user')->first();

        if($jobApplication->job->company_id != $company->id){

            return array('message'=>__('messages.not_valid_request'),'status'=>False,'statusCode'=>404,'data'=>[]);
        }

        if($jobApplication->application_status =='Applied' && $jobApplication->job->company_id == $company->id ){

            $res = (new JobApplication)->updateApplicationStatus($request->application_id,'Offer_Sent');

            (new PushNotificationService)->restaurantSendJobOfferToProvider($provider,$company,$jobApplication);
            (new FCMPushNotificationService)->newJobOffer($provider->user->id, $jobApplication->job->description);

            return array('message'=>__('Offer sent successfully.'),'status'=>True,'statusCode'=>200,'data'=>$res);

        }else{

            return array('message'=>__('messages.offer_has_been_sent_already'),'status'=>True,'statusCode'=>200,'data'=>$jobApplication);
        }
    }

    public function jobCancel($request){

        $uuid       =   $request->job_id;
        $company    =   (new Company)->company(Auth::user()->id);
        $job        =   RestaurantJob::where('uuid',$uuid)->where('company_id',$company->id)->first();

        if( $job->status == 'Cancelled' ){

            return array('message'=>__('Job already cancelled.'),'status'=>False,'statusCode'=>401,'data'=>[]);

        }elseif($job){


            if( (new RestaurantJob)->getApplicationCount($uuid, []) == 0 ){

                (new RestaurantJob)->updateStatus($job->id,'Cancelled');
                return array('message'=>__('Job has been cancelled successfully.'),'status'=>True,'statusCode'=>200,'data'=>[]);

            }

            if( (new RestaurantJob)->getApplicationCount($uuid, ['Offer_Sent','Offer_Accepted']) == 0 ){


                JobApplication::where('job_id',$job->id)->delete();
                (new RestaurantJob)->updateStatus($job->id,'Cancelled');
                return array('message'=>__('Job has been cancelled successfully.'),'status'=>True,'statusCode'=>200,'data'=>[]);


            }elseif( (new RestaurantJob)->getApplicationCount($uuid, ['Offer_Sent','Offer_Accepted']) >= 1 ){


                $res = array();
                foreach((new RestaurantJob)->getApplicationList($uuid, ['Offer_Sent','Offer_Accepted'])->applications as $application){

                    if( $application->application_status == 'Offer_Sent' ){

                        (new RestaurantJob)->updateStatus($job->id,'Cancelled');
                        (new PushNotificationService)->bookingCancellationEmailToTechnician($application->provider_id, $company->id);
                        $res['message']     =   __('Job has been cancelled successfully.');
                        $res['status']      =   True;
                        $res['statusCode']  =   200;

                    }elseif( $application->application_status == 'Offer_Accepted' ){

                        $booking    =   JobBooking::where('job_application_id',$application->id)->first('id');
                        (new RestaurantJob)->updateStatus($job->id,'Cancelled');
                        $res   =   $this->jobBookingCancellation($booking->id);

                    }
                }

                return array('message'=>$res['message'],'status'=>$res['status'],'statusCode'=>$res['statusCode'],'data'=>[]);
            }


        }else{

            return array('message'=>__('Job not found.'),'status'=>False,'statusCode'=>404,'data'=>[]);
        }
    }

    public function jobBookingCancellation($booking_id){

        $booking    =   JobBooking::where('id',$booking_id)->with('job.company.location.state','provider.user')->first();

        if( $booking->status == 'Pending' ){


            $isSameDay      =   (new TimeService)->isSameDay($booking->job->start_at);

            if( $isSameDay  ==  true ){


                (new RestaurantJob)->updateStatus($booking->job->id,'Cancelled');

                $tax    =   isset($booking->job->company->location->state->tax)?$booking->job->company->location->state->tax:18;
                $data   =   $this->getInvoice($booking->rate, $tax);


                $invoice    =   (new Invoice)->createInvoice($booking->provider,$booking,$booking->job,$booking->job->company->business_name,$data['data']);
                (new InvoiceItem)->createInvoiceItems($data['line_items'],$invoice->id);
                // Creating Charge
                $company        =   Company::find($invoice->company_id);
                (new StripeController)->createCharge($invoice, $company->stripe_customer_id, Auth::user(), 'Invoice');

                (new PushNotificationService)->bookingCancellationEmailToTechnician($booking->provider->id, $company->id);
                (new FCMPushNotificationService)->bookingCancellationEmailToTechnician($booking->provider->id, $company->id);

                (new PushNotificationService)->bookingCancellationEmailToRestaurant($company->id,$booking->provider);

            }else{


                (new RestaurantJob)->updateStatus($booking->job->id,'Cancelled');
                // What in case of older job more than one day.

            }

            return array('message'=>__('Job has been cancelled successfully.'),'status'=>True,'statusCode'=>200,'data'=>[]);

        }elseif( in_array($booking->status,['In-Progress','Puase','Invoiced','Complete']) ){


            return array('message'=>__('Job can not be cancelled.'),'status'=>False,'statusCode'=>401,'data'=>[]);
        }

    }

    public function getInvoice($rate, $tax){

        $tax_amount =   ($tax / 100) * $rate;
        $data       =   [
            'sub_total' =>  $rate,
            'tax'       =>  $tax_amount,
            'total'     =>  $rate + $tax_amount
        ];

        $line_items  =   [
            ["title"     =>  "Job cancellation charges fee 1 hour at technician rate.",
            "quantity"  =>  1,
            "unit"      =>  "unit",
            "price"     =>  $rate,
            "sub_total" =>  $rate,
            "files"     => ""]
        ];

        return  array('data'=>$data, 'line_items'=>$line_items );
    }
}
