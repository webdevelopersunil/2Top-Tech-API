<?php

namespace App\Http\Controllers\API\Restaurant;

use Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\JobFile;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Models\RestaurantJob;
use App\Models\JobApplication;
use App\Http\Service\GoogleMap;
use App\Http\Controllers\Controller;
use App\Http\Requests\CancelJobRequest;
use App\Http\Requests\JobPostingRequest;
use App\Http\Service\PushNotificationService;
use App\Repositories\RestaurantJobRepository;
use App\Http\Requests\JobApplicationIdRequest;

class RestaurantJobController extends Controller
{

    /** @var RestaurantJobRepository */
    private $restaurantJobRepository;

    public function __construct(RestaurantJobRepository $restaurantJobRepository){

        $this->restaurantJobRepository  = $restaurantJobRepository;
    }

    public function jobs(Request $request){

        $company    =   (new Company)->company(Auth::user()->id);
        $query      =   RestaurantJob::query()->with('service')
                        ->with('booking.invoice')
                        ->with('booking.provider.documents','booking.provider.user')
                        ->where('company_id',$company->id);

        if ($request->query('status')) {
            $query->whereIn('status', $request->status);
        }
        if ($request->query('text')) {
            $query->where('description','like', '%'.$request->text.'%');
        }
        if ($request->query('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        $responseData = $query->paginate(10);

        $data['Inprogress']     =   (new RestaurantJob)->getStatusCount($company->id, 'InProgress');
        $data['Cancelled']      =   (new RestaurantJob)->getStatusCount($company->id, 'Cancelled');
        $data['Pending']        =   (new RestaurantJob)->getStatusCount($company->id, 'Pending');
        $data['Completed']      =   (new RestaurantJob)->getStatusCount($company->id, 'Completed');
        $data['jobs']           =   $responseData;

        return common_response( __('messages.success'), True, 200, $data );
    }

    public function jobPost(JobPostingRequest $request){

            $data               =   $request->except(['restaurant_image','cusine','files_id']);
            $company_id         =   Company::where('user_id',Auth::user()->id)->first('id');
            $data['company_id'] =   $company_id->id;
            if(Auth::user()->email_verified_at == null || Auth::user()->email_verified_at == ''){
                return common_response( __('Please verify your email.'), False, 402, [] );
            }
            $job          =   (new RestaurantJob)->createJob($data);

            (new JobFile)->saveJobFiles($request->files_id,$job->id);
            (new Equipment)->saveJobEquipments($request->equipments_id,$job->id);

            // Fetch Providers in Radius
            $response  = [];
            $response = (new GoogleMap)->fetchInRadiusRecords($data['latitude'],$data['longitude'],$data['service_id'],$job->id);
            $response['requestType'] = "newPost";
            (new PushNotificationService)->sendEmailNotification($response);

            $responseData['uuid'] = $job->uuid;

        return common_response( __('messages.job_created'), True, 200, $responseData );
    }


    public function mailNearByProviders(){

        $emails = User::join('providers','providers.user_id','=','users.id')->get('email');

        if(count($emails) > 0){

            foreach($emails as $email){
                $details['email'] = $email;
                $details['message'] = "Message";

                dispatch(new \App\Jobs\EmailJob($details));
            }
        }
    }


    public function jobDetail(Request $request){

        if(!isset($request->uuid)){
            return common_response( 'Job id fields is require', False, 400, [] );
        }

        $jobDetail = (new RestaurantJob)->jobDetailRO($request->uuid);
        $message = !empty($jobDetail) ? __('messages.success') : __('messages.no_record_found');

        return common_response( $message, True, 200, $jobDetail );
    }

    public function jobCancel(CancelJobRequest $request){

        $res   =   $this->restaurantJobRepository->jobCancel($request);
        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function sendOffer(JobApplicationIdRequest $request){

        $res   =   $this->restaurantJobRepository->sendOffer($request);
        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function getJobApplications(Request $request){

        $company = (new Company)->company(Auth::user()->id);
        $data['job_applications']    =   (new JobApplication)->JobApplicationsRO($company->id,$request->uuid);
        $data['job_applications_count'] = count($data['job_applications']);

        return common_response( __('messages.success'), True, 200, $data);
    }

    public function updateJob(JobPostingRequest $request){

        $res   =   $this->restaurantJobRepository->updateJob($request);
        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }
}
