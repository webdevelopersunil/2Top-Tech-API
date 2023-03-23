<?php

namespace App\Http\Controllers\API;

use Auth;
use Stripe;
use Exception;
use App\Models\Plans;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\SubscriptionPlan;
use App\Http\Controllers\Controller;
use App\Http\Service\WebhookService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CompanyPaymentsRequest;

class StripeController extends Controller
{
    public function craeteSubscription(CompanyPaymentsRequest $request)
    {

        $responseData = [];

        try {

            $user = Auth::user();
            $planDetail = Plans::where('id', $request->plan_id)->first();
            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $customer = Stripe\Customer::create(array(
                "email" => $user->email,
                "name" => $user->display_name,
                'source' => $request->card_token
            ));

            $subscriptions = Stripe\Subscription::create([
                'customer'      => $customer->id,
                'items' => [
                    ['price' => $planDetail->plan_price_id],
                ],
            ]);

            if (($subscriptions && $subscriptions['status'] && $subscriptions['status'] == 'active') || ($request->plan == '1')) {

                // Sending Mail to Restaurant and Admin
                $details = array('email' => $user->email);
                // dispatch(new \App\Jobs\SubscriptionJob($details));

                $expire_at  =  $planDetail->type == 'monthly' ? Carbon::now()->addMonth(1) : Carbon::now()->addMonth(12);
                $company    =   Company::where('user_id', $user->id)->first('id');
                Company::where('id', $company->id)->update(['stripe_customer_id' => $customer->id, 'subscription_status' => 'active', 'stripe_subscription_id' => $subscriptions->id, 'expires_at' => $expire_at]);
                $subscriberPlan = (new SubscriptionPlan)->createSubrcriptionPlan($planDetail, $company->id, $expire_at);

                $message            =   __('messages.subscribed_successfully');
                $responseData       =   (new Company)->getSubscriptionPlanStatus($company->id);
                $status_code        =   200;
                $status             =   True;
            }

        } catch (\Stripe\Exception\InvalidRequestException $e) {

            // Since it's a decline, \Stripe\Exception\CardException will be caught
            $status =   $e->getError()->code;
            $status_code = $e->getHttpStatus();
            $message = $e->getError()->message;
        } catch (\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            $status = $e->getHttpStatus();
            echo 'Type is:' . $e->getError()->type;
            $status_code = $e->getError()->code;
            $message = $e->getError()->message;
        }

        return common_response($message, $status, $status_code, $responseData);
    }

    public function createCharge($invoice, $stripeCustomerId, $user, $description){

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $amount = str_replace([',', '.'], ['', ''], floor($invoice->sub_total));

        $charge = Stripe\Charge::create ([
            "amount" => $amount*100,
            "currency" => "usd",
            "customer" => $stripeCustomerId,
            "metadata" => ["res_email" => $user->email],
            "description" => $description
        ]);

        return $charge;
    }

    public function webhook(){
        (new WebhookService)->index();
    }

    public function cancelSubscription(Request $request){

        $company    =   Company::where('user_id',Auth::user()->id)->first();

        if( $company->stripe_subscription_id != '' || $company->stripe_subscription_id != Null ){

            $subscriptionStatus =   [];
            try {

                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $subscription = \Stripe\Subscription::retrieve($company->stripe_subscription_id);
                $subscription->cancel();

                // Update the subscription status of Restaurant Owner
                (new Company)->updateSubscriptionStatus($company->id,'cancelled');

                $message            =   __('Restaurant Subscribed plan has been canceled Successfully.');
                $status             =   True;
                $status_code        =   200;
                $subscriptionStatus = (new Company)->getSubscriptionPlanStatus($company->id);

            } catch (\Stripe\Exception\InvalidRequestException $e) {

                // Since it's a decline, \Stripe\Exception\CardException will be caught
                $status =   $e->getError()->code;
                $status_code = $e->getHttpStatus();
                $message = $e->getError()->message;
            } catch (\Stripe\Exception\CardException $e) {
                // Since it's a decline, \Stripe\Exception\CardException will be caught
                $status = $e->getHttpStatus();
                echo 'Type is:' . $e->getError()->type;
                $status_code = $e->getError()->code;
                $message = $e->getError()->message;
            }

            return common_response($message, $status, $status_code, $subscriptionStatus);

        }else{

            return common_response( __('User have not Subscribed to any plans yet.'), False, 400, [] );
        }

    }

    public function cardLists(Request $request){

        $company    =   Company::where('user_id',Auth::user()->id)->first('stripe_customer_id');
        $card      =   array();
        if( $company->stripe_customer_id != '' || $company->stripe_customer_id != Null ){

            try {

                // $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                // $stripe->customers->allSources( $company->stripe_customer_id, ['object' => 'card', 'limit' => 3] );

                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $sources = $stripe->customers->allSources( $company->stripe_customer_id, ['object' => 'card'] );

                foreach ($sources->data as $source) {
                    $card['brand']      =   $source->brand;
                    $card['last4']      =   $source->last4;
                    $card['exp_month']  =   $source->exp_month;
                    $card['exp_year']   =   $source->exp_year;
                    // $card['fingerprint']=   $source->fingerprint;
                }

                $message        =   __('Restaurant cards list has been retreived Successfully.');
                $status         =   True;
                $status_code    =   200;

            } catch (\Stripe\Exception\InvalidRequestException $e) {

                // Since it's a decline, \Stripe\Exception\CardException will be caught
                $status =   $e->getError()->code;
                $status_code = $e->getHttpStatus();
                $message = $e->getError()->message;
            } catch (\Stripe\Exception\CardException $e) {
                // Since it's a decline, \Stripe\Exception\CardException will be caught
                $status = $e->getHttpStatus();
                echo 'Type is:' . $e->getError()->type;
                $status_code = $e->getError()->code;
                $message = $e->getError()->message;
            }

            return common_response($message, $status, $status_code, $card);

        }else{

            return common_response( __('User have not Subscribed to any plans yet.'), False, 400, $card );
        }

    }

    public function updateCard_old(Request $request){

        $company        =   Company::where('user_id',Auth::user()->id)->first('stripe_customer_id');
        $card_token     =   $this->getCardToken();
        $cardId         =   'card_1MRC8eCtcJnsPYPIpX1GPiz7';

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = \Stripe\Customer::retrieve($company->stripe_customer_id);

        $paymentMethod = \Stripe\PaymentMethod::retrieve($customer->default_source);

        $paymentMethod->update($cardId);
        return $paymentMethod;

    }

    public function updateCard(Request $request) {

        // Set your Stripe API key
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {

            if( !isset($request->card_token) && $request->card_token == '' ){
                return common_response( __('Card token can not be empty.'), False, 404, []);
            }
            $company        =   Company::where('user_id',Auth::user()->id)->first('stripe_customer_id');
            $card_token     =   $request->card_token;
            // $card_token     =   $this->getCardToken();

            $customer       =   \Stripe\Customer::retrieve($company->stripe_customer_id);

            $cardDetail     =   $this->getActiveCard($company->stripe_customer_id);
            if(isset($cardDetail->id)){
                // deleteing the old card which will be replaced with new Card
                \Stripe\Customer::deleteSource( $company->stripe_customer_id, $cardDetail->id, [] );
            }

            \Stripe\Customer::createSource( $company->stripe_customer_id, ['source' => $card_token] );

            $message        =   __('Card has been successfully Updated');
            $status         =   True;
            $status_code    =   200;

        } catch (\Stripe\Exception\InvalidRequestException $e) {

            // Since it's a decline, \Stripe\Exception\CardException will be caught
            $status =   $e->getError()->code;
            $status_code = $e->getHttpStatus();
            $message = $e->getError()->message;
        } catch (\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            $status = $e->getHttpStatus();
            echo 'Type is:' . $e->getError()->type;
            $status_code = $e->getError()->code;
            $message = $e->getError()->message;
        }

            return common_response($message, $status, $status_code, []);
    }

    public function getActiveCard($stripe_customer_id){

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $sources = $stripe->customers->allSources( $stripe_customer_id, ['object' => 'card'] );

        foreach ($sources->data as $source) {
            $card      =   $source;
        }

        return isset($card) ? $card : null;
    }

    public function getCardToken(){
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $response = \Stripe\Token::create(array(
                    "card" => array(
                        "number"    => 4242424242424242,
                        "exp_month" => 12,
                        "exp_year"  => 2026,
                        "cvc"       => 123
                    )
                ));
        return $response->id;
    }

    public function createAutoCharge($invoice, $stripeCustomerId, $user, $description){

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $amount = str_replace([',', '.'], ['', ''], floor($invoice->sub_total));

        try {
            $charge = Stripe\Charge::create ([
                "amount" => $amount*100,
                "currency" => "usd",
                "customer" => $stripeCustomerId,
                "metadata" => ["res_email" => $user->email],
                "description" => $description
            ]);

            // Log successful charge details
            Log::info("Stripe charge successful: " . json_encode($charge));
            return $charge;

        } catch (\Exception $e) {
            // Log failed charge details
            Log::error("Stripe charge failed: " . $e->getMessage());
            return $e;
        }
    }
}
