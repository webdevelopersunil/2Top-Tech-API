<?php

namespace App\Http\Service;

use App\Models\Invoice;
use App\Models\JobBooking;
use App\Models\WorkLog;

class PlatformFeesService
{
    public function calculatePlatformFees($invoice_id){

        $invoice    =   Invoice::where('id',$invoice_id)->first();
        $technicianPayOut   =   $invoice->total_amount - $invoice->tax;

        $platform_charges   =   10;
        $fees   =   ($platform_charges/100)*$technicianPayOut;
        return $technicianPayOut-$fees;
    }

    public function getTotalTimeWorked($booking_id){

        $time_in_minutes    =   WorkLog::where('booking_id',$booking_id)->sum('interval_time');
        $time_in_hours      =   ceil($time_in_minutes / 60);
        $provider_rate      =   JobBooking::where('id',$booking_id)->first('rate');

        $data   =   [
            'provider_rate' =>  $provider_rate->rate,
            'time_in_hours' =>  $time_in_hours,
            'time_in_minutes'=> $time_in_minutes,
            'total_amount'  =>  $time_in_hours * $provider_rate->rate,
        ];
        return $data;
    }
}
