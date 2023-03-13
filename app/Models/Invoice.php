<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Queue\Worker;

class Invoice extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'uuid',
        'invoice_number',
        'booking_id',
        'provider_id',
        'company_id',
        'approved_by',
        'billing_name',
        'billing_address',
        'service_id',
        'sub_total',
        'tax',
        'total_amount',
        'status',
    ];

    protected $hidden = [
        'id',
        'booking_id',
        'provider_id',
        'company_id',
        'approved_by',
    ];

    public function invoiceItems(){

        return $this->hasMany(InvoiceItem::class,'invoice_id','id');
    }

    public function job(){

        return $this->hasOne(JobBooking::class,'id','booking_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function booking(){

        return $this->hasOne(JobBooking::class,'id','booking_id');
    }

    public function company(){
        return $this->hasOne(Company::class,'id','company_id')->with('location', 'file');
    }

    public function getDetail($invoice_id){

        $invoice    =   self::where('uuid',$invoice_id)->with('company','booking.job')->first();
        return  $invoice;
    }

    public function updateInvoiceStatus($invoice_id,$status){

        $invoice        =   self::find($invoice_id);
        $invoice->status=   $status;
        $invoice->save();
    }

    public function foundIfExist($booking_id,$provider_id){

        $response   =   self::where( ['provider_id'=>$provider_id, 'booking_id'=>$booking_id] )->first();

        return $response;
    }

    public function getInvoiceNumber(){

        // $id = DB::getPdo()->lastInsertId();
        $id             =   Self::latest()->first();
        $invoice_number =   'INV' . str_pad($id->id, 7, '0', STR_PAD_LEFT);

        return $invoice_number;
    }

    public function invoiceView($uuid){

        return  Invoice::where('uuid',$uuid)
                    ->with('invoiceItems.files.file')
                    ->with('booking.job.service','worklogs','booking.job.equipments.equipment.file')
                    ->with('booking.job.company.location.state')
                    ->with('booking.provider.documents.document','booking.provider.user')
                    ->first();
    }

    public function getPendingInvoices($company_id){
        return  Invoice::where('company_id', $company_id)->where('status', 'pending')->with('invoiceItems.files.file')->first();
    }

    public function pendingInvoices($company_id){
        return  Invoice::where('company_id', $company_id)->where('status', 'pending')->first();
    }

    public function createInvoice($provider,$booking,$job,$billing_name,$data){

        $invoice = new Invoice;
        $invoice->invoice_number    =   $this->getInvoiceNumber();
        $invoice->booking_id        =   $booking->id;
        $invoice->provider_id       =   $provider->id;
        $invoice->company_id        =   $job->company_id;
        $invoice->approved_by       =   Null;
        $invoice->billing_name      =   $billing_name;
        $invoice->billing_address   =   $provider->address;
        $invoice->service_id        =   $job->service_id;
        $invoice->sub_total         =   $data['sub_total'];
        $invoice->tax               =   $data['tax'];
        $invoice->total_amount      =   $data['total'];
        $invoice->status            =   'Pending';
        $invoice->save();

        return $invoice;
    }

    public function providerStats($provider){

        return  self::select(
                DB::raw("(sum(total_amount)) as total_amount"),
                DB::raw("(DATE_FORMAT(created_at, '%M')) as month, (DATE_FORMAT(created_at, '%Y')) as year"))
            ->orderBy('created_at')
            ->where(['provider_id'=>$provider,'status'=>'Paid'])
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%Y')"))->get();
    }

    public function stats(){
        return  self::select(
                    DB::raw("(sum(total_amount)) as total_amount"),
                    DB::raw("(DATE_FORMAT(created_at, '%m-%Y')) as month_year"))
                ->orderBy('created_at')
                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%Y')"))->get();
    }

    public function getInvoiceStatus($company_id,$status){

        $invoices['invoices']   =   self::where( [ 'company_id'=>$company_id, 'status'=>$status ] )->with('booking.provider.documents.document')
                                    ->orderBy('id','DESC')->paginate(10);
        $invoices['stats']      =   $this->stats();
        return  $invoices;
    }

    public function checkProviderBankInfoStatusank($invoice_id){

        $provider = Invoice::where('uuid',$invoice_id)->with('booking')->first();
        return $provider;
    }

    public function calculateTimeAmount($booking){

        $totalLogTime   =   WorkLog::where('booking_id',$booking->id)->sum('interval_time');

        $tax            =   18;
        $sub_total      =   ($totalLogTime/60) * $booking->rate;
        $total_amount   =   (($tax/100) * $sub_total) + $sub_total;

        $data   =   array(
            'sub_total'     =>  $sub_total,
            'tax'           =>  $tax,
            'total_amount'  =>  $total_amount
        );

        return  $data;
    }

    public function provider(){

        return $this->hasOne(Provider::class,'id','provider_id')->with('user', 'documents');
    }
    public function service(){

        return $this->hasOne(Service::class,'id','service_id');
    }
    public function worklogs(){
        return $this->hasMany(WorkLog::class,'booking_id','id');
    }
}
