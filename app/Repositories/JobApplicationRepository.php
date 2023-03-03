<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\Provider;
use App\Models\JobBooking;
use App\Models\RestaurantJob;
use App\Models\JobApplication;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Service\PushNotificationService;
use App\Http\Service\FCMPushNotificationService;

/**
 * Class JobApplicationRepository
 * @package App\Repositories
 * @version November 7, 2022, 11:36 am UTC
*/

class JobApplicationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'uuid',
        'job_id',
        'provider_id',
        'application_status',
        'comment',
        'rate_type',
        'rate'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable(){

        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(){

        return JobApplication::class;
    }

    public function applyJob($request){

        $user           =   Auth::user();
        $provider       =   (new Provider)->provider($user->id);

        $responseData   =   [];

        if($provider->status!='approved' || $provider->status == null){

            $message        = __('messages.unapproved_provider');
            $status_code    = 400;
            $status         = False;

        }else{

            $job    =   (new RestaurantJob)->jobDetailRO($request->job_id);
            if( empty($job) || $job == Null ){
                return array('message' => __('messages.non_exist_error'), 'status'=>False,'statusCode' => 402, 'data' => [] );
            }

            $foundIfExist   =   JobApplication::where(['provider_id'=>$provider->id,'job_id'=>$job->id])->first();
            if($foundIfExist){
                return array('message' => __('messages.already_applied'), 'status'=>False,'statusCode' => 402, 'data' => ['application_status'=>$foundIfExist->application_status] );
            }

            $applliedJobs   =   $this->getJobsList($provider->id,['Applied','Offer_Sent']);
            $timeSlotStatus =   $this->differentiateTimeSlot($job,$applliedJobs);
            if($timeSlotStatus ==  True ){
                return array('message' => __('Particular time slot is already taken'), 'status'=>False,'statusCode' => 402, 'data' => [] );
            }

            $responseData = (new JobApplication)->createnewJobApplication($provider->id,$job->id,$request->rate_type,$request->rate,'Applied',$request->comment);

            // Email to Restaurant about Provider applied for job
            $restaurant = Company::where('id',$job->company_id)->with('user')->first();

            (new PushNotificationService)->providerapplyingJob( $restaurant->user->email, $user ,$job->id);
            (new FCMPushNotificationService)->applyJob($user,$restaurant->user->id);

            $message        = __('messages.provider_applied_job');
            $status_code    = 200;
            $status         = True;
        }
        $application_status = !empty($responseData->application_status) ? $responseData->application_status : '';

        return array('message' => $message, 'status'=>$status,'statusCode' => $status_code, 'data' => ['application_status'=>$application_status] );
    }

    public function getJobsList($provider_id,$status){
        return JobApplication::where('provider_id',$provider_id)->whereIn('application_status',$status)->pluck('job_id');
    }

    public function differentiateTimeSlot($job, $ids){
        if( count($ids) >= 1 ){

            $result = RestaurantJob::whereBetween('start_at',[$job->start_at,$job->end_at])->whereIn('id',$ids)->get();

            if( $result == Null ){
                return True;
            }else{
                return False;
            }
        }else{
            return False;
        }
    }

    public function applicationAccept($request, $user){

        $user               =   Auth::user();
        $provider           =   (new Provider)->provider($user->id);
        $job_application    =   (new JobApplication)->detail($request->application_id);

        if($job_application == null || empty($job_application)){

            return array('message' => __('messages.record_not_found'),'status'=>False,'statusCode' => 404,'data' =>[]);
        }else{

            // $jobRestaurant = (new RestaurantJob)->getJobAllDetail($job_application->job_id);
            $jobRestaurant = RestaurantJob::where('id',$job_application->job_id)->with('company','service','company.user','company.location')->first();
        }

        if($provider->id == $job_application->provider_id && $job_application->application_status=='Offer_Sent'){

            if($request->application_status == 'Offer_Accepted'){

                $isAvailable   =   (new RestaurantJob)->checkProviderAvailabilityTimeSlot($request->application_id, $provider->id);

                if($isAvailable == 0){

                    $status     = (new JobApplication)->updateApplicationStatus($request->application_id,'Offer_Accepted');
                    $jobBooking = (new JobBooking)->findIfExist($job_application->id);

                    JobApplication::where('job_id',$job_application->job_id)->where('provider_id','!=',$provider->id)->update(['application_status'=>'Offer_Canceled']);
                    JobApplication::where('job_id',$job_application->job_id)->where('provider_id','!=',$provider->id)->delete();

                    if(empty($jobBooking)){
                        (new JobBooking)->createBooking($job_application);
                        (new RestaurantJob)->updateStatus($job_application->job_id,'InProgress');
                    }

                }else{

                    return array('message' => __('Job can not be accepted for this time slot.'),'status'=>False,'statusCode'=>402,'data' =>[]);
                }


            }else if($request->application_status == 'Offer_Rejected'){

                $status = (new JobApplication)->updateApplicationStatus($request->application_id,'Offer_Rejected');
            }

            $details = [
                'toEmail'           => $jobRestaurant->company->user->email,
                'name'              =>$user->first_name.' '.$user->first_name,
                'restaurant_name'   =>!empty($jobRestaurant->company->location->restaurant_name) ? $jobRestaurant->company->location->restaurant_name : '',
                'service_name'      =>$jobRestaurant->service->name,
            ];

            (new PushNotificationService)->sendMailJobOfferAcceptedReject($request->application_status,$details);
            (new FCMPushNotificationService)->applicationAccept($jobRestaurant->company->user->id, $request->application_status);

            return array('message' => __('messages.offer_status_updated'),'status'=>True,'statusCode'=>200,'data' =>$status);

        }else{

            return array('message' => __('messages.request_was_unacceptable'),'status'=>False,'statusCode'=>402,'data'=>[]);
        }
    }
}
