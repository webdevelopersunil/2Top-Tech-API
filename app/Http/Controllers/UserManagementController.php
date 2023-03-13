<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\DataTables\UserManagementDataTable;
use Carbon\Carbon;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserManagementDataTable $dataTable, Request $request)
    {
        $pageTitle = __('messages.list_form_title',['form' => __('messages.provider')] );

        if(!empty($request->status)){
            $pageTitle = __('messages.pending_list_form_title',['form' => __('messages.provider')] );
        }
        $auth_user = authSession();
        $assets = ['datatable'];
        return $dataTable
                ->with('list_status',$request->status)
                ->render('user_management.index', compact('pageTitle','auth_user','assets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id                 =   $request->id;
        $auth_user          =   authSession();
        $taxdata            =   User::find($id);
        $pageTitle          =   trans('messages.update_form_title',['form'=>trans('messages.tax')]);
        $user_management    =   '';

        return view('user_management.create', compact('pageTitle' ,'taxdata' ,'auth_user','user_management' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user                   =   new User;
        $user->uuid             =   Str::orderedUuid();
        $user->display_name     =   $request->display_name;
        $user->email            =   $request->email;
        $user->time_zone        =   'UTC';
        $user->email_verified_at=   Carbon::now();
        $user->password         =   Hash::make($request->password);
        $user->save();

        if( isset($user->id) ){
            UserRole::updateOrCreate(['user_id'=>$user->id],['user_id'=>$user->id, 'role_id'=>$request->role]);
        }

        return redirect(route('user_management.index'))->withSuccess('Success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auth_user  =   authSession();
        $taxdata    =   User::find($id);
        $pageTitle  =   __('User Management Update');
        $user_management =   User::where('id',$id)->with('UserRole')->first();

        return view('user_management.update', compact('pageTitle','auth_user','user_management'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd('edit');
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

        $user               =   User::find($id);
        $user->display_name =   $request->display_name;
        $user->status       =   $request->status;
        $user->save();

        return redirect(route('user_management.index'))->withSuccess('Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd('destroy');
    }
}
