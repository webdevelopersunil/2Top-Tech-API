<?php

namespace App\Models;

use App\Models\Media;
use Illuminate\Support\Str;
use App\Http\Service\TimeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantJob extends Model
{

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'company_id',
        'service_id',
        'description',
        'schedule_type',
        'status',
        'start_at',
        'end_at',
        'restaurant_name',
        'restaurant_location',
        'latitude',
        'longitude',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'company_id'
    ];

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function equipments()
    {
        return $this->hasMany(JobEquipment::class, 'job_id', 'id');
    }

    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id')->with('mediaFile');
    }

    public function files()
    {
        return $this->hasMany(JobFile::class, 'job_id', 'id');
    }

    public function applications(){
        return $this->hasMany(JobApplication::class, 'job_id', 'id');
    }

    public function application(){
        return $this->hasOne(JobApplication::class, 'job_id', 'id');
    }

    public function booking(){
        return $this->hasOne(JobBooking::class, 'job_id', 'id');
    }

    public function JobCountRo($company_id){

        $count  =   self::where('company_id',$company_id)->where('status','!=','Cancelled')->get()->count();
        return $count;
    }

    public function updateStatus($job_id,$status){

        $response = self::where('id',$job_id)->update([ 'status' => $status ]);
        return $response;
    }

    public function getApplicationCount($uuid, $applicationStatus){

        return  self::where('uuid',$uuid)->with('applications')
                ->whereHas('applications',
                    function ($a) use ($applicationStatus) {
                        if( count($applicationStatus) >= 1 ){
                            $a->whereIn('application_status', $applicationStatus);
                        }
                    })->count();
    }

    public function getApplicationList($uuid, $applicationStatus){

        return  self::where('uuid',$uuid)->with('applications')
                ->whereHas('applications',
                    function ($a) use ($applicationStatus) {
                        if( count($applicationStatus) >= 1 ){
                            $a->whereIn('application_status', $applicationStatus);
                        }
                    })->first();
    }

    public function getJobAllDetail($job_id){

        $restaurantJob  =   RestaurantJob::find($job_id)
                            ->with('company','service','company.user','company.location')->first();
        return $restaurantJob;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function createJob($data){

        $restaurantJob = new self;

        $restaurantJob->service_id          =   $data['service_id'];
        $restaurantJob->description         =   $data['description'];
        $restaurantJob->schedule_type       =   $data['schedule_type'];
        $restaurantJob->start_at            =   $data['start_at'];
        $restaurantJob->end_at              =   $data['end_at'];
        $restaurantJob->restaurant_name     =   $data['restaurant_name'];
        $restaurantJob->restaurant_location =   $data['restaurant_location'];
        $restaurantJob->longitude           =   $data['longitude'];
        $restaurantJob->latitude            =   $data['latitude'];
        $restaurantJob->company_id          =   $data['company_id'];
        $restaurantJob->save();

        return $restaurantJob;
    }

    public function providerJobsLatest($provider,$text,$service_id){
        $currentTime    =   (new TimeService)->currentTime();
        $query  =   self::query()->select(DB::raw('
                    restaurant_jobs.id,
                    restaurant_jobs.uuid,
                    restaurant_jobs.service_id,
                    restaurant_jobs.company_id,
                    restaurant_jobs.schedule_type,
                    restaurant_jobs.status,
                    restaurant_jobs.description,
                    restaurant_jobs.start_at,
                    restaurant_jobs.created_at,
                    restaurant_jobs.restaurant_location,
                    restaurant_jobs.latitude,
                    restaurant_jobs.longitude,
                    SQRT(POW(69.1 * (restaurant_jobs.latitude - '.$provider->latitude.'), 2) + POW(69.1 * ('.$provider->longitude.'-restaurant_jobs.longitude) * COS(restaurant_jobs.latitude / 57.3), 2)) AS distance'))
                    ->havingRaw('distance <='.$provider->preferred_distance)
                    ->join('provider_services','restaurant_jobs.service_id','=','provider_services.service_id')
                    ->where('provider_services.provider_id',$provider->id)
                    ->where('restaurant_jobs.status','Pending')
                    // ->where('restaurant_jobs.start_at','>=',$currentTime)
                    ->where(function($query) use ($currentTime) {
                        if ('restaurant_jobs.schedule_type' == 'ASAP') {
                            return $query->where('restaurant_jobs.schedule_type', 'ASAP');
                        }
                        if ('restaurant_jobs.schedule_type' == 'Schedule') {
                            return $query->where('restaurant_jobs.start_at','>=',$currentTime);
                        }
                    })
                    ->where(function($query) use ($service_id) {
                        if (!empty($service_id) && $service_id != '') {
                            return $query->where('restaurant_jobs.service_id',$service_id);
                        }
                    })
                    ->orderBy('restaurant_jobs.id', 'DESC')
                    ->with('company.file')->with('service');

        if ($text != Null && !empty($text)) {
            $query->where('restaurant_jobs.description','like', '%'.$text.'%');
        }

        return $query->paginate(10);
    }

    public function jobs($provider){
        $currentTime    =   (new TimeService)->currentTime();
        $data   =   Self::query()->select('restaurant_jobs.*',DB::raw('restaurant_jobs.id,restaurant_jobs.uuid,restaurant_jobs.service_id,restaurant_jobs.company_id,restaurant_jobs.description, SQRT(POW(69.1 * (restaurant_jobs.latitude - '.$provider->latitude.'), 2) + POW(69.1 * ('.$provider->longitude.'-restaurant_jobs.longitude) * COS(restaurant_jobs.latitude / 57.3), 2)) AS distance'))
                    ->havingRaw('distance <='.$provider->preferred_distance)
                    ->join('provider_services','restaurant_jobs.service_id','=','provider_services.service_id')
                    ->where('provider_services.provider_id',$provider->id)
                    ->where('restaurant_jobs.status','Pending')
                    // ->where('restaurant_jobs.start_at','>=',$currentTime)
                    ->with('service')->with('files','files.fileDetail')
                    ->where(function($query) use ($currentTime) {
                        if ('restaurant_jobs.schedule_type' == 'ASAP') {
                            return $query->where('restaurant_jobs.schedule_type', 'ASAP');
                        }
                        if ('restaurant_jobs.schedule_type' == 'Schedule') {
                            return $query->where('restaurant_jobs.start_at','>=',$currentTime);
                        }
                    })
                    ->orderBy('restaurant_jobs.id', 'DESC')
                    ->with('company.file','company.location')
                    ->take(10);

        return $data->get();
    }

    public function jobDetail($uuid){

        $provider   =   Provider::where('user_id',Auth::user()->id)->first('id');
        $jobDetail  =   Self::leftJoin('job_applications', 'job_applications.job_id', '=', 'restaurant_jobs.id')
                        ->leftJoin('job_bookings','job_bookings.job_application_id','=','job_applications.id')
                        ->where('restaurant_jobs.uuid',$uuid)
                        ->select(
                            'restaurant_jobs.*',
                            // 'job_applications.application_status as application_status',
                            // 'job_bookings.status as job_bookings_status',
                            // 'job_bookings.duration as booking_duration',
                            // 'job_bookings.rate_type as booking_rate_type',
                            // 'job_bookings.rate as booking_rate',
                            // 'job_bookings.uuid as booking_id'
                            )
                        ->with('equipments.equipment','equipments.equipment.file')
                        ->with('service')->with('company','files','files.fileDetail')->first();

        $job=   RestaurantJob::where('uuid',$uuid)->first();
        $application     =   JobApplication::where(['job_id'=>$job->id,'provider_id'=>$provider->id])->first();

        if(isset($application)){
            $jobDetail->application_status    =   $application->application_status;
        }else{
            $jobDetail->application_status    =   Null;
        }

        return $jobDetail;
    }

    public function jobDetailRO($uuid){

        $jobDetail  =   Self::where('uuid',$uuid)
                        ->with('files','files.fileDetail','service','equipments.equipment.file')
                        ->with('applications','applications.providerDetail')
                        ->first();

        return $jobDetail;
    }

    public function jobsRO($company_id){

        $jobs =     Self::where('company_id', $company_id)
                    ->leftjoin('job_applications','job_applications.job_id','=','restaurant_jobs.id')
                    ->select('restaurant_jobs.*', DB::raw('COUNT(job_applications.id) as total_applicants') )
                    ->whereIn('status', ['Pending','InProgress'])->with('service')
                    ->groupBy('restaurant_jobs.id')
                    ->limit(10)->orderBy('start_at', 'DESC')->get();

        return $jobs;
    }

    public function getStatusCount($company_id,$status){
        return RestaurantJob::query()->with('service')->where('company_id',$company_id)->where('status',$status)->count();
    }

    public function checkProviderAvailabilityTimeSlot($application_uuid, $provider_id){

        $_application        =   JobApplication::where('uuid', $application_uuid)->with('job')->first();
        $isTimeSlotBooked   =   JobApplication::where('provider_id',$provider_id)->with('job')->with('booking')
                                ->whereHas('booking', function ($a) { $a->where('status', 'In-Progress'); })
                                ->where('application_status','Offer_Accepted')->count();

        if( $isTimeSlotBooked == 0 ){

            return $isTimeSlotBooked;

        }elseif( $isTimeSlotBooked >= 1 ){

            $applications       =   JobApplication::where('provider_id',$provider_id)->with('job')->with('booking')
                                    ->whereHas('booking', function ($a) { $a->where('status', 'In-Progress'); })
                                    ->whereHas('job', function ($q) { $q->where('status','InProgress'); })
                                    ->where('application_status','Offer_Accepted')->first();

            if(isset($applications->job->schedule_type)){
                if( $applications->job->schedule_type == 'ASAP' ){

                    return $isTimeSlotBooked;

                }elseif( $applications->job->schedule_type == 'Schedule' ){

                    $res    =   (new TimeService)->isDateBetween($_application->job->start_at, $applications->job->start_at, $applications->job->end_at);

                    return  ($res == true) ? $isTimeSlotBooked : 0;
                }
            }else{
                $isTimeSlotBooked = 0;
            }


            return $isTimeSlotBooked;

        }
    }
}
