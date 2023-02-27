<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function Response($message=null,$statusCode=null,$data=null)
    {
        if(!empty($data))
        {
            return Response::json([
                'message' => $message,
                'data'     => $data,
            ], $statusCode);
        }

        return Response::json([
            'message' => $message,

        ], $statusCode);

    }
}
