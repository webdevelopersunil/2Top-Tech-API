<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Company extends Model
{
    // use HasFactory, Uuids;

    use HasApiTokens, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'business_name',
        'logo_file_id',
        'status',
        'visit_datetime',
        'stripe_customer_id',
        'stripe_subscription_id',
        'referred_by',
        'expires_at',
        'subscription_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'id',
        'stripe_customer_id',
        'stripe_subscription_id',
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function location(){
        return $this->hasOne(CompanyLocation::class,'company_id','id');
    }

    public function plan(){
        return $this->hasOne(SubscriberPlan::class,'company_id','id');
    }

    public function cuisines(){
        return $this->hasMany(RestaurantCuisine::class, 'restaurant_id','id');
    }

    public function file(){
        return $this->hasOne(File::class, 'id','logo_file_id');
    }

    public function equipments(){
        return $this->hasMany(Equipment::class, 'company_id','id')->with('category');
    }
    public function subsciptionPlan(){
        return $this->hasOne(SubscriptionPlan::class, 'company_id','id')->with('plan');
    }

    public function company($user_id){

        $company = self::where('user_id',$user_id)->first();
        return $company;
    }

    public function updateSubscriptionStatus($company_id, $status){

        $company        =   Company::find($company_id);
        $company->subscription_status   =   $status;
        $company->save();

        return $company;
    }

    public function getSubscriptionPlanStatus($company_id){

        return  Company::where('companies.id',$company_id)
                ->leftjoin('subscription_plans','subscription_plans.company_id','=','companies.id')
                ->leftjoin('plans','plans.id','=','subscription_plans.plan_id')
                ->select(
                    'companies.subscription_status as subscription_status',
                    'plans.type as type',
                    'plans.title as title'
                )->first();
    }

}
