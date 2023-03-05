<?php

namespace App\Http\Controllers\API\Provider;

use Carbon\Carbon;
use App\Models\Provider;
use App\Models\JobBooking;
use Illuminate\Http\Request;
use App\Models\RestaurantJob;
use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProviderPaymentMethod;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index(){

        $user = Auth::user();
        $data = array();

        $provider   =   Provider::where('user_id',$user->id)->first();
        $bankDetail =   ProviderPaymentMethod::where('provider_id',$provider->id)->first();

        $data['provider_status']    =   $provider->status;
        $data['email_verification'] =   (Auth::user()->email_verified_at != null) ? True : False;
        $data['bank_status']        =   $bankDetail ? True : False ;
        // $data['upcoming_bookings']  = (new JobBooking)->providerBookings($provider->id,['Invoiced','Complete','Cancelled']);
        $data['upcoming_bookings']  = (new JobBooking)->providerBookings($provider->id,['Invoiced','Cancelled']);
        $data['offers']             = (new JobApplication)->providerOffers($provider->id,['Offer_Sent']);
        $data['jobs']               = (new RestaurantJob)->jobs($provider);

        return common_response( __('messages.success'), True, 200, $data );
    }
}
