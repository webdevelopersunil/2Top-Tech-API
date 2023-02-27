<?php

namespace App\Http\Controllers\API;

use App\Models\StaticData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaticDataRequest;

class StaticDataController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StaticDataRequest $request){

        $data = (new StaticData)->fetchRecords($request->type);

        return common_response( __('messages.data_retrieved_successfully'), True, 200, $data );
    }
}
