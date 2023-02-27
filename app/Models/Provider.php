<?php

namespace App\Models;

use App\Models\User;
use App\Models\State;
use App\Traits\Uuids;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
            'uuid',
            'user_id',
            'bussiness_name',
            'contact_number',
            'address',
            'city',
            'zipcode',
            'state_id',
            'dob',
            'ssn',
            'experience_years',
            'education',
            'previous_employer',
            'referral',
            'trade_education',
            'bio',
            'preferred_distance',
            'insurance',
            'trade_organization',
            'hourly_rate',
            'weekend_rate',
            'status',
            'avg_rating',
            'bank_info_status',
            'longitude',
            'latitude',
            'fcm_token'
    ];

    protected $hidden = [
        'id',
        'user_id'
    ];

    public static function boot(){

        parent::boot();
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function updateBankStatus($id){
        $provider = self::find($id);
        $provider->bank_info_status = 'True';
        $provider->save();
    }

    public static function create($request){

        $user                   =   new User;
        $user->uuid             =   Str::orderedUuid();
        $user->email            =   $request->email;
        $user->first_name       =   $request->first_name;
        $user->last_name        =   $request->last_name;
        $user->password         =   Hash::make($request->password);
        $user->contact_number   =   $request->phone;
        $user->city             =   $request->city;
        $user->profile_status   =   'incomplete';
        $user->save();

        return $user;
    }

    public function getProfile($provider_id){

        $provider = Self::join('users', 'users.id', 'providers.user_id')
                    ->leftJoin('provider_documents','provider_documents.provider_id','=','providers.id')
                    ->leftJoin('files','files.id','=','provider_documents.file_id')
                    // ->leftjoin('job_bookings','job_bookings.provider_id','=','providers.id')
                    // ->leftjoin('ratings','ratings.booking_id','=','job_bookings.id')
                    ->where('providers.id',$provider_id)
                    ->select(
                        'users.first_name as provider_first_name',
                        'users.email',
                        'users.last_name as provider_last_name','providers.*',
                        'files.name as provider_certification_license',
                        'providers.avg_rating as avg_rating',
                        // DB::raw( 'AVG( ratings.rate ) as avg_rating')
                    )
                    ->with('documents','services','documents','states')
                    // ->with('booking.rating.ratingby')
                    ->first();

        return $provider;
    }


    public function getRatings($provider_id){

        return  JobBooking::where('job_bookings.provider_id', $provider_id)
                ->join('providers','providers.id','=','job_bookings.provider_id')
                ->join('users','users.id','=','providers.user_id')
                ->leftjoin('ratings','ratings.booking_id','=','job_bookings.id')
                ->leftjoin('companies','companies.user_id','=','ratings.rating_by')
                ->leftjoin('files','files.id','=','companies.logo_file_id')
                ->where('ratings.id','!=',null)
                ->Select(
                    'files.name as company_profile_pic',
                    'ratings.*'
                )
                ->get(10);
    }



    public function states(){
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function booking(){
        return $this->hasMany(JobBooking::class, 'provider_id', 'id');
    }

    public function services(){
        return $this->hasMany(ProviderService::class, 'provider_id','id')->with('service');
    }

    public function documents(){
        return $this->hasMany(ProviderDocument::class, 'provider_id','id')->with('document');
    }

     public function user()
    {
         return $this->belongsTo(User::class, 'user_id','id');
    }


    public function providerPatymentMethod()
    {
        return $this->hasOne(\App\Models\ProviderPaymentMethod::class, 'provider_id', 'id');
    }

    public function provider($user_id){

        $data = Self::where('user_id',$user_id)->first();

        return $data;
    }
}
