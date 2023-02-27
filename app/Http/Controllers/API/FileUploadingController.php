<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\File;
use App\Http\Requests\DeleteFileRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FileUploadRequest;

class FileUploadingController extends Controller
{
    public function upload(FileUploadRequest $request){

        $response = [];
        try{
            $dir = getDirectory($request->file_type);

            if($dir == False){
                return common_response( __('messages.undefined_file_type'), False, 400, []);
            }else{
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

            $message        = __('messages.file_uploaded');
            $status_code    = 200;
            $status         =  True;

        } catch (Exception $e) {

            $message        = $e->getMessage();
            $status_code    = $e->getCode();
            $status         =   False;
        }

        return common_response( $message, $status, $status_code, $response );
    }

    public function deleteFile(DeleteFileRequest $request){

        $file = File::where('id',$request->file_id)->first();
        if($file){
            $path = explode('/',$file->path);
            $uuid = $path[count($path)-1];
        }else{
            return common_response( __('messages.record_not_deleted'), False, 404, [] );
        }

        if($uuid == Auth::user()->uuid){

            $fileNameArr = explode('/',$file->name);
            $fileAddr = str_replace('{uuid}',Auth::user()->uuid,getDirectory($request->file_type)).'/'.$fileNameArr[count($fileNameArr)-1];

            if(Storage::disk('public')->exists($fileAddr)){
                Storage::disk('public')->delete($fileAddr);
                File::where('id',$request->file_id)->delete();
            }else{
                return common_response( __('messages.record_not_deleted'), False, 404, [] );
            }

            $message        = __('messages.msg_deleted',['name' => 'File'] );
            $status_code    = 200;
            $status         = True;

        }else{
            $message        = __('auth.request_failed');
            $status_code    = 400;
            $status         = False;

        }
        return common_response( $message, $status, $status_code, [] );
    }
}
