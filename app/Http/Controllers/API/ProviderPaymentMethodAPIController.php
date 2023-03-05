<?php

namespace App\Http\Controllers\API;

use Response;
use App\Models\Provider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProviderPaymentMethod;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\AppBaseController;
use App\Http\Service\PushNotificationService;
use App\Repositories\ProviderPaymentMethodRepository;
use App\Http\Requests\API\CreateProviderPaymentMethodAPIRequest;
use App\Http\Requests\API\UpdateProviderPaymentMethodAPIRequest;

/**
 * Class ProviderPaymentMethodController
 * @package App\Http\Controllers\API
 */

class ProviderPaymentMethodAPIController extends AppBaseController
{
    /** @var  ProviderPaymentMethodRepository */
    private $providerPaymentMethodRepository;

    public function __construct(ProviderPaymentMethodRepository $providerPaymentMethodRepo)
    {
        $this->providerPaymentMethodRepository = $providerPaymentMethodRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @OA\Get(
     *      path="/providerPaymentMethods",
     *      summary="Get a listing of the ProviderPaymentMethods.",
     *      tags={"ProviderPaymentMethod"},
     *      description="Get all ProviderPaymentMethods",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/definitions/ProviderPaymentMethod")
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $providerPaymentMethods = $this->providerPaymentMethodRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($providerPaymentMethods->toArray(), 'Provider Payment Methods retrieved successfully');
    }

    /**
     * @param CreateProviderPaymentMethodAPIRequest $request
     * @return Response
     *
     * @OA\Post(
     *      path="/providerPaymentMethods",
     *      summary="Store a newly created ProviderPaymentMethod in storage",
     *      tags={"ProviderPaymentMethod"},
     *      description="Store ProviderPaymentMethod",
     *      @OA\Parameter(
     *          name="body",
     *          in="path",
     *          description="ProviderPaymentMethod that should be stored",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/definitions/ProviderPaymentMethod"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateProviderPaymentMethodAPIRequest $request)
    {
        $input = $request->all();
        $povider = Provider::where('user_id',\Auth::user()->id)->first('id');
        $input['provider_id'] = $povider->id;
        $input['account_holder_name'] = Auth::user()->first_name.' '.Auth::user()->last_name;

        $providerPaymentMethod = ProviderPaymentMethod::where('provider_id',$povider->id)->first();

        if(empty($providerPaymentMethod)){
            $input['uuid'] = Str::orderedUuid();
            $input['account_number'] = Crypt::encryptString($input['account_number']);
            $providerPaymentMethod = $this->providerPaymentMethodRepository->create($input);
            $message    =   __('messages.provider_payment_method_saved_successfully');
        }else{
            $input['account_number'] = Crypt::encryptString($input['account_number']);
            ProviderPaymentMethod::where('id',$providerPaymentMethod->id)->update($input);
            $message    =   "Provider payment method updated successfully.";
        }
        //Send Email to Admin
        (new PushNotificationService)->bankInfoUpdatedMailTOAdmin($providerPaymentMethod);
        //Updating Bank Info Status
        (new Provider)->updateBankStatus($povider->id);

        return common_response( $message, True, 200, ['uuid'=>$providerPaymentMethod->uuid] );

    }

    /**
     * @param int $id
     * @return Response
     *
     * @OA\Get(
     *      path="/providerPaymentMethods/{id}",
     *      summary="Display the specified ProviderPaymentMethod",
     *      tags={"ProviderPaymentMethod"},
     *      description="Get ProviderPaymentMethod",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of ProviderPaymentMethod",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/definitions/ProviderPaymentMethod"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($uuid)
    {
        /** @var ProviderPaymentMethod $providerPaymentMethod */
        // $providerPaymentMethod = $this->providerPaymentMethodRepository->find($id);
        $providerPaymentMethod = ProviderPaymentMethod::where('uuid',$uuid)->first('provider_id');

        if (empty($providerPaymentMethod)) {
            return $this->sendError('Provider Payment Method not found');
        }

        $providerDetail = (new Provider)->getProfile($providerPaymentMethod->provider_id);
        // $providerPaymentMethod['account_number']= Crypt::decryptString($providerPaymentMethod['account_number']);
        return common_response( __('messages.provider_payment_method_retrieved_successfully'), True, 200, $providerDetail );
    }

    public function getAccountDetail(){

        $provider   =   Provider::where('user_id',Auth::user()->id)->first('id');
        $bankDetail =   ProviderPaymentMethod::where('provider_id',$provider->id)->first();
        $bankDetail->account_number    =    Crypt::decryptString($bankDetail->account_number);
        return common_response( __('messages.provider_payment_method_retrieved_successfully'), True, 200, $bankDetail );
    }

    /**
     * @param int $id
     * @param UpdateProviderPaymentMethodAPIRequest $request
     * @return Response
     *
     * @OA\Put(
     *      path="/providerPaymentMethods/{id}",
     *      summary="Update the specified ProviderPaymentMethod in storage",
     *      tags={"ProviderPaymentMethod"},
     *      description="Update ProviderPaymentMethod",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of ProviderPaymentMethod",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Parameter(
     *          name="body",
     *          in="path",
     *          description="ProviderPaymentMethod that should be updated",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/definitions/ProviderPaymentMethod"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateProviderPaymentMethodAPIRequest $request)
    {
        $input = $request->all();

        /** @var ProviderPaymentMethod $providerPaymentMethod */
        $providerPaymentMethod = $this->providerPaymentMethodRepository->find($id);

        if (empty($providerPaymentMethod)) {
            return $this->sendError('Provider Payment Method not found');
        }

        $providerPaymentMethod = $this->providerPaymentMethodRepository->update($input, $id);

        return $this->sendResponse($providerPaymentMethod->toArray(), 'ProviderPaymentMethod updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @OA\Delete(
     *      path="/providerPaymentMethods/{id}",
     *      summary="Remove the specified ProviderPaymentMethod from storage",
     *      tags={"ProviderPaymentMethod"},
     *      description="Delete ProviderPaymentMethod",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of ProviderPaymentMethod",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var ProviderPaymentMethod $providerPaymentMethod */
        $providerPaymentMethod = $this->providerPaymentMethodRepository->find($id);

        if (empty($providerPaymentMethod)) {
            return $this->sendError('Provider Payment Method not found');
        }

        $providerPaymentMethod->delete();

        return $this->sendSuccess('Provider Payment Method deleted successfully');
    }
}
