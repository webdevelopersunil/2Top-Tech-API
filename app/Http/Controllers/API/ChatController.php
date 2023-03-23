<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\RestaurantJob;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\JobBooking;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function roChatUsers(Request $request){

        $company    =   Company::where('user_id',Auth::user()->id)->first();
        $users      =   RestaurantJob::join('companies','companies.id','=','restaurant_jobs.company_id')
                        ->join('job_applications','job_applications.job_id','=','restaurant_jobs.id')
                        ->join('job_bookings','job_bookings.job_application_id','=','job_applications.id')
                        ->join('providers','providers.id','=','job_applications.provider_id')
                        ->join('users','users.id','=','providers.user_id')
                        ->where('restaurant_jobs.company_id','=',$company->id)
                        ->whereIn('job_bookings.status',['In-Progress','Pending'])
                        ->Select(DB::raw('DISTINCT users.uuid, CONCAT(users.first_name, " ", users.last_name) as name'))
                        ->get();

        return common_response(__('Users has been retrieved successfully.'), true, 200, $users);
    }

    public function providerChatUsers(){

        $provider   =   Provider::where('user_id',Auth::user()->id)->first();
        $users      =   JobBooking::join('providers','providers.id','=','job_bookings.provider_id')
                        ->join('restaurant_jobs','restaurant_jobs.id','=','job_bookings.job_id')
                        ->join('companies','companies.id','=','restaurant_jobs.company_id')
                        ->join('users','users.id','=','companies.user_id')
                        ->where('job_bookings.provider_id','=',$provider->id)
                        ->whereIn('job_bookings.status',['In-Progress','Pending'])
                        ->Select(DB::raw('DISTINCT users.uuid, display_name as name'))
                        ->get();

        return common_response(__('Users has been retrieved successfully.'), true, 200, $users);
    }
}
