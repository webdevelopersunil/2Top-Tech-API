<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{

    use HasFactory;
    protected $table = 'subscription_plans';

    protected $fillable = [
        'plan_name',
        'short_description',
        'features',
        'subscription_type',
        'company_id',
        'plan_id',
        'start_at',
        'end_at'
    ];

    protected $hidden = [];

    public function plan()
    {
        return $this->belongsTo(Plans::class, 'plan_id','id');
    }

    public function createSubrcriptionPlan($planDetail,$company_id,$expire_at)
    {
        $subscriptionPlan = new SubscriptionPlan;
        $data = [
            'plan_name' => $planDetail->title,
            'short_description' => 'Description',
            'features' =>  1,
            'subscription_type' => $planDetail->type,
            'company_id' => $company_id,
            'plan_id' => $planDetail->id,
            'start_at' => Carbon::now(),
            'end_at' => $expire_at,
        ];

        $subscriptionPlan->updateOrCreate(['company_id'=>$company_id],$data);

        return $subscriptionPlan;
    }
}
