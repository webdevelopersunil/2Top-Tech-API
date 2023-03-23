<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;

class StripeWebhookController extends Controller
{

    public function handleWebhook(Request $request){

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {

            case 'invoice.paid':
                $paymentIntent = $event->data->object;
                $customer_id = $paymentIntent->customer;

                if( !empty($customer_id) ){
                    $company        =   Company::where('stripe_customer_id',$customer_id)->first();
                    $subscription   =   SubscriptionPlan::where(['company_id'=>$company->id])->with('plan')->first();
                    $expire_at      =  $subscription->plan->type == 'monthly' ? Carbon::now()->addMonth(1) : Carbon::now()->addMonth(12);

                    SubscriptionPlan::where('id',$subscription->id)->update(['start_at'=>Carbon::now(),'end_at'=>$expire_at]);
                }

            default:
                echo 'Received unknown event type ' . $event->type;
            }

        http_response_code(200);
    }
}
