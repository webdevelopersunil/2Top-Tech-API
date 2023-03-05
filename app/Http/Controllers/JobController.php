<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\RestaurantJobDataTable;
use App\Models\JobApplication;
use App\Models\RestaurantJob;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RestaurantJobDataTable $dataTable, Request $request)
    {
        $pageTitle = __('messages.list_form_title',['form' => __('Jobs')] );
        if(!empty($request->status)){
            $pageTitle = __('messages.pending_list_form_title',['form' => __('Jobs')] );
        }
        $auth_user = authSession();
        $assets = ['datatable'];

        return  $dataTable->with('list_status',$request->status)
                ->render('restaurantjob.index', compact('pageTitle','auth_user','assets'));
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
    public function show(RestaurantJobDataTable $dataTable ,$id)
    {
        $auth_user = authSession();
        $jobData   =    RestaurantJob::where('id',$id)
                        ->with('company.file','service','company.user','company.location')->first();

        $providers  =   JobApplication::where('job_id',$id)->with('providerDetail.user','providerDetail.documents')->get();
        $pageTitle  =   __('messages.view_form_title',['form'=> __('Job')]);

        return $dataTable->with('provider_id',$id)
        ->render('restaurantjob.view', compact('pageTitle' ,'jobData' ,'auth_user','providers'));
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
