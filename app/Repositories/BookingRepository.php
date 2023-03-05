<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\WorkLog;
use App\Models\Provider;
use App\Models\JobBooking;
use App\Models\InvoiceItem;
use App\Models\RestaurantJob;
use App\Http\Service\GoogleMap;
use App\Http\Service\TimeService;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Service\PushNotificationService;
use App\Http\Service\FCMPushNotificationService;


class BookingRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'duration'
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
        return JobBooking::class;
    }

    public function manageWorkLog($request){

        $booking =    JobBooking::where('uuid',$request->booking_id)->first();
        if($booking == Null || !isset($booking)){
            return array('message' =>__('Booking not found.'),'status'=>False,'statusCode' => 404,'data' => []);
        }
        if($booking->status == 'Complete' || $booking->status == 'Invoiced' || $booking->status == 'Cancelled' ){

            return array('message' =>__('Booking status already '.$booking->status),'status'=>True,'statusCode' => 401,'data' => []);

        }else{

            $provider               =   (new Provider)->provider(Auth::user()->id);
            $response               =   $this->addWorkLog($request->all(),$provider->id,$booking);
            $data['booking_status'] =   isset($this->trackerStatus($booking)['data']) ? $this->trackerStatus($booking)['data'] : null;
            JobBooking::where('id',$booking->id)->update(['duration'=>$data['booking_status']['total_time']]);
            $data['work_logs']      =   (new WorkLog)->getWorkLogs($booking->id,[]);

            return array('message' => __('Success.') ,'status'=>True,'statusCode' => 200,'data' => $data);
        }
    }

    public function addWorkLog($data,$provider_id,$jobBooking){

        $lastEntryWorkLog   =   WorkLog::where('interval_time','!=',Null)->where('booking_id',$jobBooking->id)->orderBy('id','DESC')->first();
        $foundIfAny         =   WorkLog::where(['type'=>'Pause', 'booking_id'=>$jobBooking->id])->first();

        switch($data['type']) {

            case('Pause'):

                if(empty($foundIfAny) || $lastEntryWorkLog->type=='Restart' ){

                    (new JobBooking)->updateBookingStatus($jobBooking->job_id,$provider_id,array('status'=>'Puase'));
                    $intervalTime   =   $this->getdifferenceBetweenTwoDateTime($jobBooking);
                    (new WorkLog)->createWorkLog('Pause',$data['comment'],$jobBooking->id,$intervalTime,Null);
                }

                break;

            case('Restart'):

                if($lastEntryWorkLog->type=='Pause'){

                    (new JobBooking)->updateBookingStatus($jobBooking->job_id,$provider_id,array('status'=>'In-Progress'));
                    $intervalTime   =   $this->getdifferenceBetweenTwoDateTime($jobBooking);
                    (new WorkLog)->createWorkLog('Restart',$data['comment'],$jobBooking->id,$intervalTime,Null);
                }

                break;

            case('WorkLog'):

                (new JobBooking)->updateBookingStatus($jobBooking->job_id,$provider_id,array('status'=>'In-Progress'));
                (new WorkLog)->createWorkLog('WorkLog',$data['comment'],$jobBooking->id,Null,Null);

                break;

            case('Completed'):

                $response['total_time'] =   WorkLog::where('booking_id',$jobBooking->id)->whereNotIn('type',['Restart','WorkLog'])->sum('interval_time');

                $status     =   [
                    'status'    =>  'Complete',
                    'end_at'    =>  (new TimeService)->currentTime(),
                    'duration'  =>  $response['total_time']
                ];

                $intervalTime   =   $this->getdifferenceBetweenTwoDateTime($jobBooking);
                (new WorkLog)->createWorkLog('Pause',$data['comment'],$jobBooking->id,$intervalTime,'Completed');
                (new JobBooking)->updateBookingStatus($jobBooking->job_id,$provider_id,$status);

                break;

            default:
                $response['message'] = 'Something went wrong.';
        }

        $response['message']    =   __('messages.success');

        return $response;
    }

    public function updateWorkLog($request, $user){

        $data       =   $request->all();
        $provider   =   Provider::where('user_id',$user->id)->first('id');

        $workLog    =   WorkLog::where(['uuid'=>$request->uuid, 'type' => 'WorkLog'])->with('booking')
                        ->whereHas('booking', function ($a) use ($provider){
                            $a->whereNotIn('status', ['Complete','Cancelled','Invoiced']);
                            $a->where('provider_id', $provider->id);
                        })->first();

        if($workLog && $workLog->booking->status != 'Complete'){

            $workLog->update(['comment'=>$data['comment']]);

            return array('message' =>  __('Worklog has been updated successfully.'),'data' => '','statusCode' => 200,'status'=>True);

        }else{

            return array('message' =>  __('Worklog can not be updated.'),'data' => '','statusCode' => 400,'status'=>False);
        }

    }

    public function getdifferenceBetweenTwoDateTime($jobBooking){

        $currentTime        =   (new TimeService)->currentTime();
        $workLogs           =   WorkLog::whereNotIn('type',['WorkLog'])->where('booking_id',$jobBooking->id)->orderBy('created_at','DESC')->first();

        if($workLogs){
            $intervalTime   =   (new TimeService)->differenceBetweenTwoDateTimeInMinutes($workLogs->log_time, $currentTime);
        }else{
            $intervalTime   =   (new TimeService)->differenceBetweenTwoDateTimeInMinutes($jobBooking->start_at, $currentTime);
        }
        return $intervalTime;
    }

    public function trackerStatus($request)
    {
        $booking    =   $this->wherefirst(['uuid'=> $request->uuid]);

        if(!$booking){

            return array('message' =>  __('Provided data is invalid.'),'data' => '','statusCode' => 400,'status'=>False);

        }else{

            $data['timer_status']   =   $booking->status;
            $data['starting_time']  =   $booking->start_at;
            $data['total_time']     =   $this->getTotalTrackedTime($booking->id);
            $data['invoice_status'] =   $this->getInvoiceActiveStatus($booking->id);
            $data['work_logs']      =   (new WorkLog)->getWorkLogs($booking->id,['WorkLog']);

            return array('message' =>'Success.','data' => $data,'statusCode' => 200,'status'=>True);
        }
    }

    public function getTotalTrackedTime($booking_id){

        $workLogsCount  =   WorkLog::where('booking_id',$booking_id)->whereIn('type',['Pause'])->get()->count();
        $currentTime    =   (new TimeService)->currentTime();

        if($workLogsCount >= 1){

            $recentRestartWorklog   =   WorkLog::where('booking_id',$booking_id)->whereNotIn('type',['WorkLog'])->orderBy('id','DESC')->first();

            if($recentRestartWorklog){
                if($recentRestartWorklog->type=='Restart'){
                    $addOnTime = (new TimeService)->differenceBetweenTwoDateTimeInMinutes($recentRestartWorklog->log_time,$currentTime);
                    $minutes    =   WorkLog::where('booking_id',$booking_id)->whereIn('type',['Pause'])->sum('interval_time');
                    $minutes    =   isset($addOnTime) ? $addOnTime + $minutes : $minutes ;
                }else if($recentRestartWorklog->type=='Pause'){
                    $minutes    =   WorkLog::where('booking_id',$booking_id)->whereIn('type',['Pause'])->sum('interval_time');
                }
            }

        }else{

            $booking    =   JobBooking::where('id',$booking_id)->first();
            $minutes    =   (new TimeService)->differenceBetweenTwoDateTimeInMinutes($booking->start_at,$currentTime);
        }

        return $minutes;
    }

    public function getInvoiceActiveStatus($booking_id){

        $status = Invoice::where('booking_id',$booking_id)->first('status');
        return isset($status) ? True : False;
    }

    public function bookingInvoice($user, $data){

        $provider   =   (new Provider)->provider($user->id);
        $booking    =   (new JobBooking)->getDetail($data['booking_id']);
        $foundIfAny =   (new WorkLog)->checkIfWorkLog($booking->id);
        $foundIfExist = (new Invoice)->foundIfExist($booking->id,$provider->id);
        $job        =   RestaurantJob::where('id',$booking->job_id)->with('service')->first();
        $company    =   Company::where('id',$job->company_id)->with('location','user')->first();


        if(empty($foundIfAny) || $foundIfAny == Null){

            return array('message' => __('messages.work_log_submission_pending') ,'status'=>False,'statusCode' => 402,'data' => []);

        }else if(!empty($foundIfExist) || $foundIfExist != null){

            return array('message' => __('messages.invoice_has_been_already_craeted') ,'status'=>False,'statusCode' => 401,'data' => []);
        }

        if($provider->id === $booking->provider_id){
            $billingName = !empty($company->location->restaurant_name) ? $company->location->restaurant_name : "Restaurant Owner";

            $invoice = (new Invoice)->createInvoice($provider,$booking,$job,$billingName,$data);

            if($invoice){
                JobBooking::where('id',$invoice->booking_id)->update(['status'=>'Invoiced']);
                (new RestaurantJob)->updateStatus($job->id,'Completed');
            }
            (new InvoiceItem)->createInvoiceItems($data['line_items'],$invoice->id);
            (new PushNotificationService)->jobCompletionEmailNotificationTechnician($job,$user,$company);
            (new FCMPushNotificationService)->technicainSubmittedInvoice($job,$company->user_id);

            return array('message' => __('Invoice has been created successfully.') ,'status'=>True,'statusCode' => 200,'data' => []);

        }else{

            return array('message' => __('messages.action_is_unauthorized') ,'status'=>False,'statusCode' => 401,'data' => []);
        }
    }

    public function bookingInvoicesList($user){

        $data   =   array();
        $providerData           =   Provider::where('user_id', $user->id)->first();
        $data['invoicesList']   =   Invoice::where('provider_id', $providerData->id)->where('status', 'Paid')
                                    ->with('booking.job.company.file')->orderBy('id', 'DESC')->paginate(10);
        $data['stats']          =   (new Invoice)->providerStats($providerData->id);

        if($data){
            return array('message' => __('messages.provider_booking_invoice_list') ,'status'=>True,'statusCode' => 200,'data' => $data);
        }else{
            return array('message' => __('messages.request_was_unacceptable') ,'status'=>False,'statusCode' => 402,'data' => []);
        }
    }

    public function startTracking($request){

        $user       =   Auth::user();
        $booking    =   (new JobBooking)->getDetail($request->booking_id);
        $job        =   RestaurantJob::where('id',$booking->job_id)->first();
        $provider   =   (new Provider)->provider($user->id);
        $status     =   (new GoogleMap)->validateProviderLocation($job->latitude,$job->longitude,$request->all());
        $company    =   Company::where('id',$job->company_id)->with('user')->first();

        (new FCMPushNotificationService)->sendStartTrackingNotification($company->user->id,$job);

        if( $status === True ){

            $startTime  =   (new TimeService)->currentTime();
            $booking    =   (new JobBooking)->updateStartTrackingBookingStatus($job->id,$provider->id,"In-Progress",$startTime);

            return array('message' => __('messages.success') ,'status'=>True,'statusCode' => 200,'data' => []);

        }elseif( $status === False ){

            return array('message' => __('messages.not_nearby_of_job') ,'status'=>False,'statusCode' => 401,'data' => []);
        }
    }
}
