<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Provider;
use Illuminate\Console\Command;
use App\Http\Service\TimeService;
use Illuminate\Support\Facades\Log;
use App\Http\Service\PushNotificationService;
use App\Http\Controllers\API\StripeController;
use App\Http\Service\FCMPushNotificationService;
use App\Models\Payment;

class AutoApproveInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto_approve:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Approve Invoices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $unpaidInvoices =   Invoice::where('status','Pending')->get();

        if(count($unpaidInvoices) >= 1){

            foreach($unpaidInvoices as $index => $invoice){

                $diff       =   (new TimeService)->differenceBetweenTwoDateTime($invoice->created_at, Carbon::now());
                $company    =   Company::with('user')->whereNotNull('stripe_customer_id')->find($invoice->company_id);

                if($diff >= 8 && $invoice->sub_total <= 999999 && $company != null){

                    if( $invoice->sub_total != 0 || $invoice->sub_total != null ){

                        $createCharge   =   new StripeController;
                        $response   =   $createCharge->createAutoCharge($invoice, $company->stripe_customer_id, $company->user, 'Invoice');

                        if( $response->paid == true ){

                            (new Payment())->savePayment($invoice, $response, $company);
                            (new Invoice)->updateInvoiceStatus($invoice->id,"Paid");
                            (new PushNotificationService)->jobCompletionEmailNotification($company->user,$invoice);
                            $provider    =    Provider::where('id',$invoice->provider_id)->first('user_id');
                            (new FCMPushNotificationService)->restaurantApprovedInvoice($provider->user_id,$invoice,$invoice->booking->job);

                        }else{

                            (new PushNotificationService)->jobCompletionAutoChargeIssue($company->user,$invoice);
                        }
                    }
                }
            }
        }
        $this->info('Invoice has been Paid.');
    }
}
