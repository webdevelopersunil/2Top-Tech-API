<?php

namespace App\Http\Controllers\API\Restaurant;

use App\Models\ToDo;
use Illuminate\Http\Request;
use App\Http\Requests\ToDoRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ToDoUpdateStatusRequest;

class ToDoController extends Controller
{
    public function index()
    {
        $toDos  =   ToDo::where('user_id', Auth::user()->id)
                    ->where('status','!=','Close')
                    ->orderBy('created_at', 'DESC')
                    ->groupBy('service_id')->with('service')
                    ->paginate(20);

        return common_response(__('Todo has been created successfully.'), true, 200, $toDos);
    }

    public function store(ToDoRequest $request)
    {
        try {

            $todo = (new ToDo())->store($request->all(), Auth::user()->id);
            return common_response(__('Todo has been created successfully.'), true, 200, []);

        } catch (\Exception $e) {

            $message    =   (isset($e->errorInfo)) ? $e->errorInfo :  __('An error occurred while creating the ToDo.');
            return common_response( $message, false, 500, []);
        }

    }

    public function updateStatus(ToDoUpdateStatusRequest $request){

        try {

            ToDo::where('uuid',$request->uuid)->update(['status'=>$request->status]);
            $status =   ToDo::where('uuid',$request->uuid)->first('status');
            return common_response(__('Todo status has been successfully updated.'), true, 200, $status);

        } catch (\Exception $e) {

            $message    =   (isset($e->errorInfo)) ? $e->errorInfo :  __('An error occurred while creating the ToDo.');
            return common_response( $message, false, 500, []);
        }

    }

}
