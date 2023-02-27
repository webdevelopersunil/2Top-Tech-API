<?php

namespace App\Repositories;

use App\Models\Company;
use App\Repositories\BaseRepository;

class CompanyRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'business_name'
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
        return Company::class;
    }

    // public function testCase($request){
    //     return array('message'=>__('messages.offer_has_been_sent_already'),'status'=>True,'statusCode'=>200,'data'=>[]);
    // }

    public function getRestaurantSubscriptionPlanStatus($company){
        return Company::where('id',$company->id)->with('subsciptionPlan')->first();
    }
}
