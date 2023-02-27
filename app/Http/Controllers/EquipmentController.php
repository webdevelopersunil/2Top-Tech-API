<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Company;
use App\Models\StaticData;
use App\DataTables\EquipmentDataTable;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EquipmentDataTable $dataTable, Request $request)
    {
        if($request && $request->id){
            $restaurant_owner_id = $request->id;
        }else{
            $restaurant_owner_id = null;
        }

        $pageTitle = __('messages.list_form_title',['form' => __('messages.equipment')] );
        $auth_user = authSession();
        $assets = ['datatable'];
        return $dataTable->with('restaurant_owner_id',$restaurant_owner_id)->render('equipment.index', compact('pageTitle','auth_user','assets','restaurant_owner_id'));
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

        $equipmentdata = Equipment::where('id', $id)->with('file')->first();
        $equipmentCategories = StaticData::where("type", 'equipment_categories')->get();

        $pageTitle = __('messages.update_form_title',['form'=> __('messages.equipment')]);
        
        if($equipmentdata == null){
            $pageTitle = __('messages.add_button_form',['form' => __('messages.equipment')]);
            $equipmentdata = new Equipment;
        }
        
        return view('equipment.create', compact('pageTitle' ,'equipmentdata' ,'auth_user', 'equipmentCategories' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $previous_file_id="";
        if(demoUserPermission()){
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
       
       $file = $request->file('file');
       if($file){
            $response = true;
            
            if($request->id){
                $previous_file_id = Equipment::where('id', $request->id)->pluck("file_id")->first();
                $file_type = $request->file_type;
                
            }

            if($response){
                // starts
                $dir = getDirectory($request->file_type);
               
                if($dir){
                    $dir = str_replace("{uuid}",\Auth::user()->uuid,$dir);
                }

                $file = $request->file('file');

                if(!Storage::exists($dir)){
                    Storage::makeDirectory($dir);
                }

                $fileName =  Storage::put($dir, $file);
                $response = File::create([
                    'name'      =>$fileName,
                    'path'      =>$dir,
                    'extension' =>$file->getClientOriginalExtension(),
                    'type'      =>$file->getMimeType(),
                    'size'      =>$file->getSize(),
                ]);
                $request['file_id'] = $response->id;
            }
            
            
       }

       $equipments = $request->all();
       
        $result = Equipment::updateOrCreate(['id' => $request->id], $equipments);
        if($previous_file_id){
            $response = $this->delete_file($previous_file_id, $file_type);
        }

        $message = __('messages.update_form',[ 'form' => __('messages.equipment') ] );
        if($result->wasRecentlyCreated){
            $message = __('messages.save_form',[ 'form' => __('messages.equipment') ] );
        }

        if($request->is('api/*')) {
            return comman_message_response($message);
        }
        return redirect(route("equipment.list", ["id" => $result->company_id]))->withSuccess($message);
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
            if(request()->is('api/*')){
                return comman_message_response( __('messages.demo_permission_denied') );
            }
            return  redirect()->back()->withErrors(trans('messages.demo_permission_denied'));
        }
        $equipment = Equipment::find($id);
        $msg= __('messages.msg_fail_to_delete',['item' => __('messages.equipment')] );
        
        if($equipment!='') {
            $equipment->delete();
            $msg= __('messages.msg_deleted',['name' => __('messages.equipment')] );
        }
        if(request()->is('api/*')){
            return comman_custom_response(['message'=> $msg , 'status' => true]);
        }
        return redirect()->back()->withSuccess($msg);
    }

     /**
     * Remove equipment photo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_file($id, $file_type)
    {
        //

        $file = File::where('id',$id)->first();
        if($file){
            $path = explode('/',$file->path);
            $uuid = $path[count($path)-1];
        }

        if($uuid == Auth::user()->uuid){

            $fileNameArr = explode('/',$file->name);
            $fileAddr = str_replace('{uuid}',Auth::user()->uuid,getDirectory($file_type)).'/'.$fileNameArr[count($fileNameArr)-1];

            if(Storage::disk('public')->exists($fileAddr)){
                Storage::disk('public')->delete($fileAddr);
                File::where('id',$id)->delete();
                return true;
            }else{
                return false;
            }

        }else{
            return false;

        }
    }
}
