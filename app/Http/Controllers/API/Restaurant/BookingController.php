<?php

namespace App\Http\Controllers\API\Restaurant;

use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\RestaurantJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Service\PushNotificationService;
use App\Http\Controllers\API\StripeController;
use App\Http\Requests\BookingCompletionRequest;
use App\Http\Service\FCMPushNotificationService;

class BookingController extends Controller
{
    public function bookingCompletion(BookingCompletionRequest $request){

        $user       =   Auth::user();
        $invoice    =   (new Invoice)->getDetail($request->invoice_id);

        if( $invoice != null ){

            if($invoice->company->stripe_customer_id == null ){

                return common_response( __('Company does not have valid customer ID'), False, 401, [] );
            }else if($invoice->status != 'Pending'){

                return common_response( __('Invoice already with status Paid'), False, 401, [] );
            }else{

                $createCharge   =   new StripeController;
                $createCharge->createCharge($invoice, $invoice->company->stripe_customer_id, $user, 'Invoice');
            }

            (new Invoice)->updateInvoiceStatus($invoice->id,"Paid");
            $invoice=(new Invoice)->getDetail($request->invoice_id);

            if($invoice->status=='Paid'){
                (new PushNotificationService)->jobCompletionEmailNotification($user,$invoice);
                $provider    =    Provider::where('id',$invoice->provider_id)->first('user_id');
                (new FCMPushNotificationService)->restaurantApprovedInvoice($provider->user_id,$invoice,$invoice->booking->job);
            }
            return common_response( __('messages.success'), True, 200, $invoice );

        }else{

            return common_response( __('Invalid Invoice Id'), False, 404, [] );
        }
    }
}
