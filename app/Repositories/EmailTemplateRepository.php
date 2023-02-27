<?php

namespace App\Repositories;

use App\Models\EmailTemplate;
use App\Repositories\BaseRepository;

/**
 * Class EmailTemplateRepository
 * @package App\Repositories
 * @version December 2, 2022, 8:18 am UTC
*/

class EmailTemplateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email_subject',
        'email_body'
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
        return EmailTemplate::class;
    }
}
