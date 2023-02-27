<?php

namespace App\Http\Service;

use App\Models\User;
use App\Models\State;
use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;


class GoogleMap
{
    public function getLatLong($data)
    {

        if (is_array($data)) {
            $state_name = State::where('id', $data['state_id'])->pluck('name')->first();
            $address = $data['address'] . ' ' . $data['city'] . ' ' . $state_name;
        } else {
            $address = $data;
        }
        $address = str_replace(" ", "+", $address);
        $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=" . $address . "&sensor=false&key=".env('GOOGLE_MAP_KEY'));
        $json = json_decode($json);

        $latlong['latitude'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $latlong['longitude'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

        return $latlong;
    }

    public function fetchInRadiusRecords($latitude, $longitude, $service_id,$job_id)
    {
        if( !empty($latitude) && !empty($longitude) ){
            $providers = DB::table('providers')
            ->join('provider_services','providers.id','=','provider_services.provider_id')
            ->join('users','providers.user_id','=','users.id')
            ->havingRaw('distance <= providers.preferred_distance')
            ->where(['providers.status'=>'approved','provider_services.service_id'=>$service_id ])
            ->select(DB::raw('users.email,users.id as user_id,providers.id as provider_id,providers.fcm_token,providers.preferred_distance, SQRT(POW(69.1 * (providers.latitude - ' . $latitude . '), 2) + POW(69.1 * (' . $longitude . '-providers.longitude) * COS(providers.latitude / 57.3), 2)) AS distance'))
            ->OrderBy('distance')->get();

            $data = [
                'providers' => $providers,
                'job_id'    => $job_id,
            ];
        }else{
            $data = [
                'providers' => [],
                'job_id'    => $job_id,
            ];
        }

        return $data;
    }

    //validate provider current lat-long if it's under range of job Lat-Long
    function validateProviderLocation($job_latitude,$job_longitude,$providerData){

        $latitude1  =   $job_latitude;
        $longitude1 =   $job_longitude;
        $latitude2  =   $providerData['latitude'];
        $longitude2 =   $providerData['longitude'];

        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;

        //Convert Miles to Meters
        $distance = $distance*1609.344;

        $jobStartDistance = (new Setting)->getDetail('JOB_START_DISTANCE');
        $jobStartDistance = (int)$jobStartDistance->value;

        return ($jobStartDistance >= $distance) ? True : False ;


      }
}
