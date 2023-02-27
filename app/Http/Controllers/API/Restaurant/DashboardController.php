<?php

namespace App\Http\Controllers\API\Restaurant;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\JobBooking;
use Illuminate\Http\Request;
use App\Models\RestaurantJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){

        $response = array();

        $company                        =   (new Company)->company(Auth::user()->id);
        $response['subscription_status']=   (new Company)->getSubscriptionPlanStatus($company->id);
        $response['email_verification'] =   (Auth::user()->email_verified_at != null) ? True : False;
        $response['job_count']          =   (new RestaurantJob)->JobCountRo($company->id);
        $response['services']           =   (new Service)->getServices();
        $response['jobs']               =   (new RestaurantJob)->jobsRO($company->id);
        $response['upcoming_jobs']      =   (new JobBooking)->upcomingJobRestaurant($company->id);
        $response['pending_invoices']   =   (new Invoice)->pendingInvoices($company->id);

        return common_response(__('messages.success'), True, 200, $response);
    }
}
