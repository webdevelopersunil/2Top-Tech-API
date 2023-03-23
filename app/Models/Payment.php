<?php

namespace App\Models;

use App\Http\Service\TimeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'payments';
    protected $fillable =
    [
        'company_id',
        'invoice_id',
        'customer_id',
        'discount',
        'total_amount',
        'payment_method',
        'datetime',
        'balance_transaction',
        'currency',
        'txn_id',
        'payment_status',
        'other_transaction_detail'
    ];

    protected $casts = [
        'customer_id'   => 'integer',
        'discount'      => 'double',
        'total_amount'  => 'double',
    ];

    public function customer(){
        return $this->belongsTo(User::class,'customer_id', 'id')->withTrashed();
    }
    public function booking(){
        return $this->belongsTo(Booking::class,'booking_id', 'id')->withTrashed();
    }
    public function scopeMyPayment($query)
    {
        $user = auth()->user();
        if($user->hasAnyRole(['admin', 'demo_admin'])){
            return $query;
        }

        if($user->hasRole('provider')) {
            return $query->whereHas('booking', function($q) use($user) {
                $q->where('provider_id', '=', $user->id);
            });
        }

        if($user->hasRole('user')) {
            return $query->where('customer_id', $user->id);
        }

        if($user->hasRole('handyman')) {
            return $query->whereHas('booking',function ($q) use($user) {
                $q->whereHas('handymanAdded',function($handyman) use($user){
                    $handyman->where('handyman_id',$user->id);
                });
            });
        }

        return $query;
    }

    public function savePayment($invoice, $response, $company){

        $payment                =   new Payment;
        $payment->company_id    =   $company->id;
        $payment->invoice_id    =   $invoice->id;
        $payment->customer_id   =   $response->customer;
        $payment->total_amount  =   $invoice->total_amount;
        $payment->payment_method=   $response->payment_method;
        $payment->datetime      =   (new TimeService)->currentTime();
        $payment->currency      =   $response->currency;
        $payment->payment_status=   $response->paid == true ? 'paid' : 'failed';
        $payment->created_at    =   (new TimeService)->currentTime();
        $payment->save();

        return $payment;
    }
}
