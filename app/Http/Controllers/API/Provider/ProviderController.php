<?php

namespace App\Http\Controllers\API\Provider;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProviderRequest;
use App\Repositories\ProviderRepository;
use App\Http\Requests\ProviderProfileRequest;

class ProviderController extends Controller
{

    /** @var ProviderRepository */
    private $providerRepository;

    public function __construct(ProviderRepository $providerRepository){

        $this->providerRepository  = $providerRepository;
    }

    public function register(ProviderRequest $request){

        $res   =   $this->providerRepository->createProvider($request);
        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function updateProfile(ProviderProfileRequest $request){

        $user   =   Auth::user();
        $res    =   $this->providerRepository->updateProfile($request, $user);
        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }

    public function profile(){

        $profile = User::where('id',Auth::user()->id)
                    ->with('provider','provider.states')
                    ->with('provider.documents','provider.documents.document')
                    ->with('provider.services','provider.services.service')
                    ->first();
        return common_response( __('messages.success'), True, 200, $profile );
    }

    public function providerRate(){

        $user   =   Auth::user();
        $res    =   $this->providerRepository->providerRateList($user);
        return common_response( $res['message'], $res['status'], $res['statusCode'], $res['data'] );
    }
}
