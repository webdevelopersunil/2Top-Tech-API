<?php

namespace App\Http\Controllers\API\Restaurant;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Type\TrueType;

class InvoiceController extends Controller
{
    public function invoiceView(Request $request){

        if( $request->uuid != '' ){

            $invoice    =   (new Invoice)->invoiceView($request->uuid);
            return common_response( __('messages.success'), True, 200, $invoice );

        }else{

            return common_response( __('messages.not_found_entry'), False, 404, [] );
        }
    }

    public function paymentList(){

        $company        =   (new Company)->company(Auth::user()->id);
        $paidInvoices   =   (new Invoice)->getInvoiceStatus($company->id,'Paid');

        return common_response( __('messages.success'), True, 200, $paidInvoices );

    }

    public function restaurantPendingInvoice(){

        $restaurant         =   Company::where('user_id', Auth::user()->id)->first('id');
        $invoicesdetails    =   (new Invoice)->getPendingInvoices($restaurant->id);

        return common_response( __('messages.provider_booking_invoice_list'), 200, True, $invoicesdetails );
    }
}
