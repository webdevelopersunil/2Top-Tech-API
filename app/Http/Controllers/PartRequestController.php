<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\PartRequestDataTable;
use App\Models\PartRequest;

class PartRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PartRequestDataTable $dataTable, Request $request)
    {
        $pageTitle = __('messages.list_form_title',['form' => __('messages.part_request')] );
        if(!empty($request->status)){
            $pageTitle = __('messages.pending_list_form_title',['form' => __('messages.provider')] );
        }
        $auth_user = authSession();
        $assets = ['datatable'];

        return  $dataTable->with('list_status',$request->status)
                ->render('partrequest.index', compact('pageTitle','auth_user','assets'));
    }

    public function partRequestStatusUpdate(Request $request){

        $id     =   $request->id;
        $status =   $request->status;
        $status =   PartRequest::where('id',$id)->update(['status'=>$status]);

        return redirect()->route('part-request.show',$id);
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
        $auth_user      =   authSession();
        $partrequest    =   PartRequest::with('provider.documents.document','file','provider.states','provider.user')->find($id);
        $pageTitle      =   __('messages.view_form_title',['form'=> __('Part Request Detail')]);

        foreach($partrequest->provider->documents as $document){
            if( $document->document_type == 'provider_profile_picture'){
                $profile_picture    =   $document->document->name;
            }
        }

        $data           =   [];

        return view( 'partrequest.view', compact('pageTitle','data','partrequest','profile_picture') );
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
