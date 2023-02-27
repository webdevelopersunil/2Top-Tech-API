<?php

namespace App\Repositories;

use App\Models\PartRequest;
use App\Repositories\BaseRepository;

/**
 * Class PartRequestRepository
 * @package App\Repositories
 * @version December 8, 2022, 12:46 pm UTC
*/

class PartRequestRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'provider_id',
        'equipment_id',
        'name',
        'sku',
        'file_id',
        'make',
        'model_no',
        'sn_number',
        'location',
        'refrigerant_type',
        'warranty_Info',
        'voltage_amps',
        'date_of_purchase',
        'filter_number',
        'comment',
        'status'
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
        return PartRequest::class;
    }
}
