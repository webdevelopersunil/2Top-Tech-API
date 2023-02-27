<?php

namespace App\Http\Controllers\API\Provider;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ProviderInvoiceRepository;

class InvoiceController extends Controller
{


    /** @var ProviderInvoiceRepository */
    private $providerInvoiceRepository;

    public function __construct(ProviderInvoiceRepository $providerInvoiceRepository){

        $this->providerInvoiceRepository  = $providerInvoiceRepository;
    }

    public function invoiceView_old(ProviderInvoiceRepository $request){

        $res   =   $this->providerInvoiceRepository->invoiceView($request);
        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function invoiceView(Request $request){

        if( $request->uuid != '' ){

            $invoice    =   (new Invoice)->invoiceView($request->uuid);
            return common_response( __('messages.success'), True, 200, $invoice );

        }else{

            return common_response( __('messages.not_found_entry'), False, 404, [] );
        }
    }

}
