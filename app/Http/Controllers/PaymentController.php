<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payment;
use App\Models\Invoice;

use App\DataTables\PaymentDataTable;
use App\Http\Service\PlatformFeesService;
use App\Models\InvoiceItem;
use App\Models\JobBooking;
use App\Models\WorkLog;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaymentDataTable $dataTable)
    {
        $pageTitle = __('messages.list_form_title',['form' => __('messages.transactions')] );
        $assets = ['datatable'];
        return $dataTable->render('payment.index', compact('pageTitle','assets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auth_user = authSession();
        $invoiceDetails     =   Invoice::where('id',$id)->where('status', 'Paid')
                                ->with("company.user","company","company.location.state","provider.states","provider.user", "service", "invoiceItems.files.file")->first();
        $pageTitle          =    __('messages.view_form_title',['form'=> __('messages.invoice_details')]);



        $data    =   (new PlatformFeesService)->getTotalTimeWorked($id);

        $tax                =   $invoiceDetails->tax;
        $sum_of_price       =   InvoiceItem::where('invoice_id',$invoiceDetails->id)->sum('price');
        $sum_of_sub_total   =   InvoiceItem::where('invoice_id',$invoiceDetails->id)->sum('sub_total');

        return view( 'payment.view', compact('pageTitle' ,'invoiceDetails','sum_of_price','sum_of_sub_total','tax','data') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
