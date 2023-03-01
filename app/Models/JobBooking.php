<?php

namespace App\Models;

use App\Http\Service\TimeService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'job_id',
        'job_application_id',
        'provider_id',
        'status',
        'rate_type',
        'rate',
        'start_at',
        'end_at',
        'duration'
    ];

    protected $hidden = [
        'id',
        'job_id',
        'provider_id',
        'job_application_id'
    ];

    public function createBooking($jobApplication){

        $job                        =   RestaurantJob::find($jobApplication->job_id);
        $duration                   =   (new TimeService)->differenceBetweenTwoDateTime($job->start_at, $job->end_at);

        $jobBooking                 =   new JobBooking;
        $jobBooking->uuid           =   Str::orderedUuid();
        $jobBooking->job_id         =   $jobApplication->job_id;
        $jobBooking->job_application_id =$jobApplication->id;
        $jobBooking->provider_id    =   $jobApplication->provider_id;
        $jobBooking->rate_type      =   $jobApplication->rate_type;
        $jobBooking->rate           =   $jobApplication->rate;
        $jobBooking->start_at       =   Null;
        $jobBooking->end_at         =   Null;
        $jobBooking->duration       =   !empty($duration) ? $duration : Null;
        $jobBooking->save();

        return $jobBooking;
    }

    public function provider(){
        return $this->belongsTo(Provider::class, 'provider_id','id');
    }

    public function rating(){
        return $this->hasOne(BookingRating::class, 'booking_id','id');
    }

    public function job(){
        return $this->belongsTo(RestaurantJob::class, 'job_id','id');
    }

    public function invoice(){
        return $this->hasOne(Invoice::class, 'booking_id','id');
    }

    public function worklogs(){
        return $this->hasMany(WorkLog::class, 'booking_id','id');
    }

    public function findIfExist($job_application_id){

        $jobBooking = JobBooking::where('job_application_id',$job_application_id)->first();
        return $jobBooking;
    }

    public function upcomingJobRestaurant($company_id,$status){

        $currentTime  = (new TimeService)->currentTime();
        $upcomingJobs = Self::join('restaurant_jobs', 'restaurant_jobs.id', 'job_bookings.job_id')
                        ->join('providers','providers.id','job_bookings.provider_id')
                        ->leftJoin('provider_documents','provider_documents.provider_id','providers.id')
                        ->leftJoin('files','files.id','provider_documents.file_id')
                        ->leftJoin('services','services.id','restaurant_jobs.service_id')
                        ->where('provider_documents.document_type','=','provider_profile_picture')
                        ->where('restaurant_jobs.company_id', $company_id)
                        ->whereIn('restaurant_jobs.status', $status)
                        ->whereIn('job_bookings.status',['Pending','In-Progress'])
                        // ->where('restaurant_jobs.start_at', '>=', $currentTime)
                        ->select(
                            'job_bookings.*',
                            'files.name as provider_profile_pic',
                            'restaurant_jobs.uuid as jobId',
                            'restaurant_jobs.description',
                            'restaurant_jobs.start_at as job_start_at',
                            'restaurant_jobs.end_at as job_end_at',
                            'services.name as service_name',
                            'services.description as service_description',
                            'providers.uuid as providerId'
                            )
                        ->limit(10)->get();

        return  $upcomingJobs;
    }

    public function providerBookings($provider_id,$status){

        $currentTime    =   (new TimeService)->currentTime();
        $bookings       =   JobBooking::join('restaurant_jobs','restaurant_jobs.id','=','job_bookings.job_id')
                            ->where('job_bookings.provider_id',$provider_id)
                            ->whereNotIn('job_bookings.status',$status)
                            // ->whereDate('restaurant_jobs.start_at', '>=', $currentTime)
                            ->with('job','job.service','job.company','job.company.file','job.company.location.state')
                            ->select('job_bookings.*')
                            ->orderBy('restaurant_jobs.start_at', 'ASC')
                            ->take(10)->get();
        return $bookings;
    }

    public function updateStartTrackingBookingStatus($job_id,$provider_id,$status,$startTime){

        $jobBooking = Self::where( ['job_id'=>$job_id, 'provider_id'=>$provider_id] )->first();

        if( $jobBooking->start_at == Null || $jobBooking->start_at == '' ){
            $jobBooking->update( ['status'=>$status,'start_at'=>$startTime] );
        }

        return $jobBooking;
    }

    public function updateBookingStatus($job_id,$provider_id,$status){

        $jobBooking = Self::where( ['job_id'=>$job_id, 'provider_id'=>$provider_id] )->update($status);
        return $jobBooking;
    }

    public function getDetail($uuid){

        $detail = Self::where('uuid',$uuid)->first();
        return $detail;
    }
}
