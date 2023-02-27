<?php

namespace App\Http\Controllers\API\Provider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WorkLogRequest;
use App\Repositories\BookingRepository;
use App\Http\Requests\StartTrackingRequest;
use App\Http\Requests\UpdateWorkLogRequest;
use App\Http\Requests\BookingInvoiceRequest;

class BookingController extends Controller
{
    /** @var BookingRepository */
    private $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository  = $bookingRepository;

    }
    public function startTracking(StartTrackingRequest $request){

        $res = $this->bookingRepository->startTracking($request);

        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function workLog(WorkLogRequest $request){

        $res = $this->bookingRepository->manageWorkLog($request);

        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function updateWorkLog(UpdateWorkLogRequest $request){

        $user   =   Auth::user();
        $res    =   $this->bookingRepository->updateWorkLog($request, $user);

        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function bookingInvoice(BookingInvoiceRequest $request){

        $user       =   Auth::user();
        $data       =   $request->json()->all();

        $res = $this->bookingRepository->bookingInvoice($user, $data);

        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function bookingInvoicesList(){

        $user       =   Auth::user();
        $booking    =   $this->bookingRepository->bookingInvoicesList($user);

        return common_response( $booking['message'], $booking['status'], $booking['statusCode'], $booking['data'] );
    }

    public function trackingStatus(Request $request){

        $booking = $this->bookingRepository->trackerStatus($request);

        return common_response( $booking['message'], $booking['status'], $booking['statusCode'], $booking['data'] );
    }
}
