<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\RatingReviewDataTable;
use App\Models\BookingRating;
use App\Models\Company;
use App\Models\JobBooking;
use App\Http\Requests\API\CreateRatingReviewRequest;
use App\Http\Service\PushNotificationService;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewRatingAdminMail;
use App\Models\UserRole;

class RatingReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RatingReviewDataTable $dataTable)
    {
        $pageTitle = trans('messages.list_form_title',['form' => trans('messages.rating')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return $dataTable->render('ratingreview.index', compact('pageTitle','auth_user','assets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $id = $request->id;
        $auth_user = authSession();

        $rating_review = BookingRating::with('customer')->find($id);
        $pageTitle = trans('messages.update_form_title',['form'=>trans('messages.rating')]);

        return view('ratingreview.create', compact('pageTitle' ,'rating_review' ,'auth_user' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_old(CreateRatingReviewRequest $request)
    {
        $request['rating_by'] = auth()->user()->id;
        $request['rating_comment'] = $request->comment;

        $data = $request->all();
        $validate   =   JobBooking::where('uuid',$data['booking_id'])->first();
        if( $validate != Null ){
            $data['booking_id'] =   $validate->id;
            $responseData = BookingRating::create($data);
        }else{
            return common_response( __('Booking id not found.') , False, 404, [] );
        }
        $details = [];

        $company_details = JobBooking::where('uuid', $request->booking_id)->with('job', 'job.company')->first();
        if($company_details){
            $details['restaurant_name'] = $company_details->job->restaurant_name;
            $details['rate'] = $request->rate;
            $details['email_template'] = "job_rating";

            Mail::to(env('ADMIN_EMAIL'))->send(new NewRatingAdminMail($details));

        }
            $message        =   __('messages.ratingCreated');
            $status_code    =   200;
            $status         =   True;

        return common_response( $message, $status, $status_code, $responseData );
    }


    public function store(CreateRatingReviewRequest $request)
    {
        $data = $request->all();

        $data['rating_by']      =   Auth()->user()->id;
        $data['rating_comment'] =   $request->comment;
        $roleDetail      =   (new UserRole())->getRole();

        $validate   =   JobBooking::where('uuid',$data['booking_id'])->first();
        if( $validate ){

            $data['booking_id'] =   $validate->id;

            $found      =   BookingRating::where( ['rating_by'=>$data['rating_by'], 'booking_id'=> $validate->id] )->first();

            if($found){

                return common_response( __('Rating already existed against this Booking.') , False, 400, [] );

            }else{

                $responseData = BookingRating::create($data);
                (new BookingRating)->calculateAverageRating($data);
                $company_details = JobBooking::where('uuid', $request->booking_id)->with('job', 'job.company')->first();

                if($roleDetail->roleDetail->name == 'provider'){
                    if($company_details){
                        $details['company_name']    =   $company_details->job->company->business_name;
                        $details['rate']            =   $request->rate;
                        $details['rating_by']       =   $data['rating_by'];

                        (new PushNotificationService)->ratingToCompany($details);
                        // Mail::to(env('ADMIN_EMAIL'))->send(new NewRatingAdminMail($details));
                    }
                }
                return common_response( __('messages.ratingCreated'), True, 200, [] );
            }

        }else{
            return common_response( __('Booking id not found.') , False, 404, [] );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $rating_review = BookingRating::find($id);

        if( $rating_review!='') {

            $rating_review->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.rating')] );
        }
        return redirect()->back()->withSuccess($msg);
    }

    public function action(Request $request){
        $id = $request->id;

        $document  = BookingRating::withTrashed()->where('id',$id)->first();
        $msg = __('messages.not_found_entry',['name' => __('messages.rating')] );
        if($request->type == 'restore') {
            $document->restore();
            $msg = __('messages.msg_restored',['name' => __('messages.rating')] );
        }
        if($request->type === 'forcedelete'){
            $document->forceDelete();
            $msg = __('messages.msg_forcedelete',['name' => __('messages.rating')] );
        }
        return comman_custom_response(['message'=> $msg , 'status' => true]);
    }
}
