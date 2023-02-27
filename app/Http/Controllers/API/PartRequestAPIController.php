<?php

namespace App\Http\Controllers\API;

use Response;
use App\Models\Provider;
use App\Models\PartRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PartRequestRepository;
use App\Http\Requests\API\CreatePartRequestAPIRequest;
use App\Http\Requests\API\UpdatePartRequestAPIRequest;
use App\Http\Service\PushNotificationService;

/**
 * Class PartRequestController
 * @package App\Http\Controllers\API
 */

class PartRequestAPIController extends AppBaseController
{
    /** @var  PartRequestRepository */
    private $partRequestRepository;

    public function __construct(PartRequestRepository $partRequestRepo)
    {
        $this->partRequestRepository = $partRequestRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @OA\Get(
     *      path="/partRequests",
     *      summary="Get a listing of the PartRequests.",
     *      tags={"PartRequest"},
     *      description="Get all PartRequests",
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
     *                  @OA\Items(ref="#/definitions/PartRequest")
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
        $partRequests = $this->partRequestRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return common_response( __('Part Requests retrieved successfully'), True, 200, $partRequests->toArray());
    }

    public function getList(Request $request){

        $provider       =   Provider::where('user_id',Auth::user()->id)->first('id');
        $query          =   PartRequest::query()->latest()->with('file');

        $query->where('provider_id',$provider->id);

        if ($name = $request->name) {
            $query->where('name','Like','%'.$name.'%');
        }
        if ($status = $request->status) {
            $query->where('status',$status);
        }

        return common_response( __('Part Requests retrieved successfully'), True, 200, $query->paginate(10) );
    }

    /**
     * @param CreatePartRequestAPIRequest $request
     * @return Response
     *
     * @OA\Post(
     *      path="/partRequests",
     *      summary="Store a newly created PartRequest in storage",
     *      tags={"PartRequest"},
     *      description="Store PartRequest",
     *      @OA\Parameter(
     *          name="body",
     *          in="path",
     *          description="PartRequest that should be stored",
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
     *                  ref="#/definitions/PartRequest"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePartRequestAPIRequest $request)
    {
        $input = $request->all();

        $input['uuid']  = Str::orderedUuid();
        $provider       = Provider::where('user_id',Auth::user()->id)->first('id');
        $input['provider_id']   =   $provider->id;
        $input['status']        =   "Pending";
        $partRequest = $this->partRequestRepository->create($input);

        (new PushNotificationService)->partRequestMailToAdmin($partRequest,$provider);

        return common_response( __('Part Request saved successfully'), True, 200, ['uuid'=>$partRequest->uuid] );

    }

    /**
     * @param int $id
     * @return Response
     *
     * @OA\Get(
     *      path="/partRequests/{id}",
     *      summary="Display the specified PartRequest",
     *      tags={"PartRequest"},
     *      description="Get PartRequest",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of PartRequest",
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
     *                  ref="#/definitions/PartRequest"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var PartRequest $partRequest */
        $partRequest = PartRequest::where('uuid',$id)->first();

        if (empty($partRequest)) {
            return $this->sendError('Part Request not found');
        }

        return common_response( __('Part Request retrieved successfully'), True, 200, $partRequest );
    }

    /**
     * @param int $id
     * @param UpdatePartRequestAPIRequest $request
     * @return Response
     *
     * @OA\Put(
     *      path="/partRequests/{id}",
     *      summary="Update the specified PartRequest in storage",
     *      tags={"PartRequest"},
     *      description="Update PartRequest",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of PartRequest",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Parameter(
     *          name="body",
     *          in="path",
     *          description="PartRequest that should be updated",
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
     *                  ref="#/definitions/PartRequest"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePartRequestAPIRequest $request)
    {
        $input = $request->all();

        /** @var PartRequest $partRequest */
        $partRequest = $this->partRequestRepository->find($id);

        if (empty($partRequest)) {
            return $this->sendError('Part Request not found');
        }

        $partRequest = $this->partRequestRepository->update($input, $id);

        return $this->sendResponse($partRequest->toArray(), 'PartRequest updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @OA\Delete(
     *      path="/partRequests/{id}",
     *      summary="Remove the specified PartRequest from storage",
     *      tags={"PartRequest"},
     *      description="Delete PartRequest",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of PartRequest",
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
        /** @var PartRequest $partRequest */
        $partRequest = PartRequest::where('uuid',$id)->first();

        if (empty($partRequest)) {
            return common_response( __('Part Request not found'), False, 401, [] );
        }

        $partRequest->delete();

        return common_response( __('Part Request deleted successfully'), False, 401, [] );
    }
}
