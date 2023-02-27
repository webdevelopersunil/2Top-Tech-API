<?php
namespace App\Http\Service;

use Carbon\Carbon;

class TimeService
{
    public function currentTime(){

        return  Carbon::now();
    }

    public function differenceBetweenTwoDateTime($startTime, $endTime){

        $start  =   Carbon::parse($startTime);
        $end    =   Carbon::parse($endTime);

        return  $start->diffInHours($end);
    }

    public function differenceBetweenTwoDateTimeInMinutes($startTime, $endTime){

        $start  =   Carbon::parse($startTime);
        $end    =   Carbon::parse($endTime);

        return  $start->diffInMinutes($end);
    }

    function isSameDay($date) {

        $dateTime = Carbon::createFromFormat('Y-m-d H:i:s', $date);

        return $dateTime->isSameDay($this->currentTime());
    }

    function isDateBetween($dateToCheck, $startDate, $endDate){

        $dateToCheck = Carbon::parse($dateToCheck);
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        return $dateToCheck->between($startDate, $endDate);
    }
}
