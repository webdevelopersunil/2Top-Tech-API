<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "job_applications";

    protected $fillable = [
        'uuid',
        'job_id',
        'provider_id',
        'application_status',
        'comment',
        'rate_type',
        'rate'
    ];

    protected $hidden = [
        'id',
        'job_id',
        'provider_id'
    ];

    protected $primaryKey = "id";

    public function job(){
        return $this->belongsTo(RestaurantJob::class, 'job_id','id')->with('service');
    }

    public function provider(){
        return $this->belongsTo(Provider::class, 'id','provider_id');
    }

    public function providerDetail(){
        return $this->hasOne(Provider::class, 'id','provider_id');
    }

    public function booking(){
        return $this->hasOne(JobBooking::class, 'job_application_id','id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function updateApplicationStatus($uuid,$status){

        $application = self::where('uuid',$uuid)->first();

        $application->application_status    =   $status;
        $application->save();

        return $application;
    }

    public function detail($id){

        $detail =   self::where('uuid',$id)->first();
        return $detail;
    }

    public function createnewJobApplication($provider_id, $job_id, $rate_type, $rate, $status, $comment){

        $jobApplication = new self;
        $jobApplication->provider_id        =   $provider_id;
        $jobApplication->job_id             =   $job_id;
        $jobApplication->rate_type          =   $rate_type;
        $jobApplication->rate               =   $rate;
        $jobApplication->application_status =   $status;
        $jobApplication->comment            =   $comment;

        $jobApplication->save();

        return $jobApplication;
    }

    public function providerOffers($provider_id,$status){

        $offers =   Self::where('provider_id',$provider_id)
                    ->whereIn('application_status',$status)->orderBy('id', 'DESC')
                    ->with('job','job.service','job.company.file')->take(10)->get();

        return $offers;
    }

    public function JobApplicationsRO($company_id,$uuid){

        $job                =   RestaurantJob::where('uuid',$uuid)->first();
        $jobApplications    =   JobApplication::leftJoin('providers','providers.id','=','job_applications.provider_id')
                                // ->leftjoin('job_bookings','job_bookings.provider_id','=','providers.id')
                                // ->leftjoin('ratings','ratings.booking_id','=','job_bookings.id')
                                ->where('job_applications.job_id',$job->id)
                                ->select(
                                    'job_applications.*',
                                    'providers.avg_rating as avg_rating',
                                    // DB::raw( 'AVG( ratings.rate ) as avg_rating')
                                    )
                                ->with('providerDetail.user','providerDetail.documents','job.service')
                                ->with('booking.rating')
                                ->get();

        return  $jobApplications;
    }
}
