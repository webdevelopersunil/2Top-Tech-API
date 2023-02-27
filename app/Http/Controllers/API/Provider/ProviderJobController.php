<?php

namespace App\Http\Controllers\API\Provider;

use Auth;
use Mail;
use Exception;
use App\Models\Company;
use App\Models\Provider;
use App\Models\JobBooking;
use App\Mail\JobStatusMail;
use Illuminate\Support\Str;
use App\Mail\JobAppliedMail;
use Illuminate\Http\Request;
use App\Models\RestaurantJob;
use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyJobRequest;
use App\Http\Service\PushNotificationService;
use App\Repositories\JobApplicationRepository;
use App\Http\Service\FCMPushNotificationService;
use App\Http\Requests\JobApplicationAcceptRequest;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Trunc;

class ProviderJobController extends Controller{

    /** @var JobApplicationRepository */
    private $jobApplicationRepository;

    public function __construct(JobApplicationRepository $jobApplicationRepository){

        $this->jobApplicationRepository  = $jobApplicationRepository;
    }

    public function jobs(Request $request){

        $provider       = Provider::where('user_id',Auth::user()->id)->first();
        $responseData   = (new RestaurantJob)->providerJobsLatest($provider, $request->text,$request->service_id);

        $message        =   __('messages.success');
        $status_code    =   200;
        $status         =   True;

        return common_response( $message, $status, $status_code, $responseData );
    }

    public function jobDetail(Request $request){

        if(!isset($request->uuid)){
            return common_response( 'Job id fields is require', False, 400, [] );
        }

        $job_detail = (new RestaurantJob)->jobDetail($request->uuid);
        return common_response( __('messages.success'), True, 200, $job_detail );
    }

    public function applyJob(ApplyJobRequest $request){

        $res   =   $this->jobApplicationRepository->applyJob($request);
        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function applicationAccept(JobApplicationAcceptRequest $request){

        $res    =   $this->jobApplicationRepository->applicationAccept($request, Auth::user());
        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function bookingList(Request $request){

        $responseData   = [];
        $per_page = 10;

        $user = Auth::user();
        $provider = Provider::where('user_id',$user->id)->first('id');

        // $all_bookings_jobs = JobApplication::where('job_applications.provider_id', $provider->id)->where('application_status', '!=', 'Applied')->with(['job','job.company.file'])->orderBy('job_applications.id','desc');

        $all_bookings_jobs = JobApplication::where('job_applications.provider_id', $provider->id)->with(['job','job.company.file'])->orderBy('job_applications.id','desc');

        $searchText = $request->query('service_name');
        $searchAvailability = $request->query('job_availability');

        if ($searchText = $request->query('service_name')) {
            $all_bookings_jobs = $all_bookings_jobs->join('restaurant_jobs', 'job_applications.job_id', '=', 'restaurant_jobs.id')
            ->join('services', 'restaurant_jobs.service_id', '=', 'services.id')
            ->where('services.name', 'LIKE', "%{$searchText}%")->with(['job']);
        }

        $all_job_details = $all_bookings_jobs->get();

        if($all_job_details && count($all_job_details)>0 || ($searchAvailability && in_array("all", $searchAvailability) ) ){
             if($all_job_details){
                foreach($all_job_details as $all_job_detail){
                    $responseData['all_bookings_jobs'][] = $all_job_detail;
                }
             }
        }else{
            $responseData['all_bookings_jobs'] = [];
        }
        $responseData['all_bookings_jobs_count'] = count($all_job_details);

        if($searchAvailability && (in_array("offers", $searchAvailability)  || in_array("open", $searchAvailability) || in_array("closed", $searchAvailability)) && !in_array("all", $searchAvailability) ){
            $responseData['all_bookings_jobs'] = [];
        }
        // when RO sent offers
        $bookings_offers = JobApplication::where('job_applications.provider_id', $provider->id)->where('application_status', 'Offer_Sent')->orderBy('job_applications.id','desc')->with(['job','job.company.file'])->get();

        $responseData['bookings_offers_count'] = count($bookings_offers);

        if($searchAvailability && in_array("offers", $searchAvailability) && $bookings_offers && count($bookings_offers)>0 && !in_array("all", $searchAvailability)){
            foreach($bookings_offers as $bookings_offer){
                $responseData['all_bookings_jobs'][] = $bookings_offer;
            }

        }
        // when job is open
        $open_bookings = JobApplication::where('job_applications.provider_id', $provider->id)->where('application_status', 'Offer_Accepted')
        ->join('job_bookings', 'job_applications.id', '=', 'job_bookings.job_application_id')
        ->where('job_bookings.status', 'In-Progress')->orderBy('job_applications.id','desc')->with(['job','job.company.file'])->get();

        $responseData['open_bookings_count'] = count($open_bookings);

        if($searchAvailability && in_array("open", $searchAvailability) && $open_bookings && count($open_bookings)>0 && !in_array("all", $searchAvailability)){
            foreach($open_bookings as $open_booking){
                $responseData['all_bookings_jobs'][] = $open_booking;
            }
        }
        // when job is completed
        $closed_bookings = JobApplication::where('job_applications.provider_id', $provider->id)->where('application_status', 'Offer_Accepted')
        ->join('job_bookings',  'job_applications.id', '=', 'job_bookings.job_application_id')
        ->where('job_bookings.status', 'Complete')->orderBy('job_applications.id','desc')->with(['job','job.company.file'])->get();

        $responseData['closed_bookings_count'] = count($closed_bookings);

        if($searchAvailability && in_array("closed", $searchAvailability) && $closed_bookings && count($closed_bookings)>0 && !in_array("all", $searchAvailability)){
            foreach($closed_bookings as $closed_booking){
                $responseData['all_bookings_jobs'][] = $closed_booking;
            }
        }
        if(array_key_exists("all_bookings_jobs", $responseData) ){
            // apply custom pagination for collected records
            $page = ! empty( $request->query('page') ) ? (int) $request->query('page'): 1;
            $total = count( $responseData['all_bookings_jobs'] ); //total items in array
            $totalPages = ceil( $total/ $per_page ); //calculate total pages
            $offset = ($page - 1) * $per_page;
            $available_records = $page * $per_page;
            $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages

            if( $offset < 0 ) $offset = 0;

            if($page >= $total){
                $responseData['all_bookings_jobs'] = [];
                $responseData['next_page'] = null;
            }else{
                $responseData['all_bookings_jobs'] = array_slice( $responseData['all_bookings_jobs'], $offset, $per_page );
                if($total > $available_records){
                    $responseData['next_page'] = $page+1;
                }else{
                    $responseData['next_page'] = null;
                }

            }
        }

        if($responseData){
            $responseData = $responseData;
            $message        = __('messages.provider_booking_list');
            $status_code    = 200;
            $status         = True;
        }else{

            $status_code    = 402;
            $status         = True;
            $message = __(__('messages.request_was_unacceptable'));
        }
        return common_response( $message, $status, $status_code, $responseData );

    }
}
