<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\User;
use App\Models\Provider;
use App\Models\UserRole;
use Illuminate\Support\Str;
use App\Http\Service\GoogleMap;
use App\Models\ProviderService;
use App\Models\ProviderDocument;
use App\Repositories\BaseRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;


class ProviderRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'address'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Provider::class;
    }

    public function createProvider($request){

        $user   =   (new Provider)->create($request);

        if($user){

            $user_id = $user->id;
            event(new Registered($user));

            if(isset($request->fcm_token) && !empty($request->fcm_token)){
                (new User)->updatefcmToken($request->fcm_token,$user_id);
            }

            //Assign role provider
            $role  =(new UserRole)->assignProviderRole($user_id);

            //Generating Auth Token
            $authDetail['api_token']   =  $user->createToken('auth_token')->plainTextToken;
            $authDetail['first_name']  =  $user->first_name;
            $authDetail['last_name']   =  $user->last_name;
            $authDetail['uuid']        =  $user->uuid;
            $authDetail['role']        =  $role->name;
            $authDetail['profile_status'] = $user->profile_status;

            return array('message' => __('Success.'), 'status'=>True,'statusCode' => 200, 'data' => $authDetail );

        }else{

            return array('message' => __('Something went wrong.'), 'status'=>False,'statusCode' => 401, 'data' => [] );
        }
    }

    public function providerRateList($user){

        $provider   =   Provider::where('user_id',$user->id)->first();
        if($provider){

            $data = [
                [   'type'	=>	"standard_rate",
                    'hourly_rate'   =>  $provider->hourly_rate,
                ],
                [   'type'	=>	"weekend_rate",
                    'hourly_rate'  =>  $provider->weekend_rate
                ]
            ];

            return array('message' => __('messages.success'), 'status'=>True, 'statusCode' => 200, 'data' => $data );
        }else{

            return array('message' => __('messages.not_found'), 'status'=>False,'statusCode' => 404, 'data' => [] );
        }
    }

    public function updateProfile($request, $user){

        $data           =   $request->except(['prfile_image','service','email','phone']);
        $ifFound        =   Provider::where('user_id',$user->id)->first('id');

        if(empty($ifFound) && !isset($ifFound) ){
            $data['uuid']   =   Str::orderedUuid();
        }

        // Generate the Lat Long with Provided Address Detail's
        $latlong            =   (new GoogleMap)->getLatLong(['city'=>$data['city'], 'address'=>$data['address'],'state_id'=>$data['state_id'] ]);
        $data['latitude']   =   $latlong['latitude'];
        $data['longitude']  =   $latlong['longitude'];
        $responseData       =   Provider::updateOrCreate(['user_id'=>$user->id],$data);


        if($responseData){
            $user->update(['first_name'=>$data['first_name'],'last_name'=>$data['last_name'],'profile_status'=>'complete']);

            if($request->has('service')){
                $servicesToArray=explode(',',$request->service);
                if(count($servicesToArray) >= 1){
                    ProviderService::where('provider_id',$responseData->id)->whereNotIn('service_id',$servicesToArray)->delete();
                    $providerServices = new ProviderService;
                    foreach($servicesToArray as $service){
                        $providerServices->updateOrCreate(['provider_id'=>$responseData->id,'service_id'=>trim($service)],['provider_id'=>$responseData->id,'service_id'=>trim($service)] );
                    }
                }
            }
            if($request->driver_license_front){
                $this->removingOldFiles($responseData->id, 'driver_license_front',$request->driver_license_front);
                $this->uploadingProviderDocument($request->driver_license_front,$responseData->id,'driver_license_front');
            }
            if($request->driver_license_back){
                $this->removingOldFiles($responseData->id, 'driver_license_back',$request->driver_license_back);
                $this->uploadingProviderDocument( $request->driver_license_back, $responseData->id, 'driver_license_back');
            }
            if($request->certification_license){
                $this->removingOldFiles($responseData->id, 'certification_license',$request->certification_license);
                $this->uploadingProviderDocument( $request->certification_license, $responseData->id, 'certification_license');
            }
            if($request->vehicle_license_plate){
                $this->removingOldFiles($responseData->id, 'vehicle_license_plate',$request->vehicle_license_plate);
                $this->uploadingProviderDocument( $request->vehicle_license_plate, $responseData->id, 'vehicle_license_plate');
            }
            if($request->provider_profile_picture){
                $this->removingOldFiles($responseData->id, 'provider_profile_picture',$request->provider_profile_picture);
                $this->uploadingProviderDocument( $request->provider_profile_picture, $responseData->id, 'provider_profile_picture');
            }

            if($request->has('provider_gallery')){

                $galleryToArray=explode(',',$request->provider_gallery);
                if(count($galleryToArray) >= 1){
                    foreach($galleryToArray as $gallery){
                        ProviderDocument::updateOrCreate(
                            [
                                'file_id'=>trim($gallery),
                                'provider_id'=>$responseData->id,
                                'document_type'=>'provider_gallery'
                            ],
                            [
                                'file_id'=>trim($gallery),
                                'provider_id'=>$responseData->id,
                                'document_type'=>'provider_gallery',
                                'is_verified'=>1
                            ]);
                    }
                }
                ProviderDocument::where(['document_type'=>'provider_gallery'])->whereNotIn('file_id',$galleryToArray)->forceDelete();
            }

            $response = Provider::where('id',$responseData->id)->with('services')->with('states')->with('documents','documents.document')->first();

            return array('message' => __('messages.profile_update'), 'status'=>True,'statusCode' => 200, 'data' => $response );

        }else{
            return array('message' => __('Something went wrong.'), 'status'=>False,'statusCode' => 401, 'data' => [] );
        }
    }

    public function uploadingProviderDocument($file_id,$provider_id,$document_type){

        ProviderDocument::updateOrCreate(['document_type'=>$document_type,'provider_id'=>$provider_id],['file_id'=>$file_id,'provider_id'=>$provider_id,'document_type'=>$document_type,'is_verified'=>1]);

        return True;
    }


    public function removingOldFiles($provider_id,$document_type,$file_id){

        $data = ProviderDocument::where(['provider_id'=>$provider_id,'document_type'=>$document_type])->with('document')->first();
        if(isset($data)){
            if($data->file_id != $file_id){
                if(isset($data['document']['name'])){

                    if(Storage::disk('public')->exists($data['document']['name'])){

                        Storage::disk('public')->delete($data['document']['name']);
                        File::where('id',$data->file_id)->delete();
                    }
                }
            }
        }

        return True;
    }
}
