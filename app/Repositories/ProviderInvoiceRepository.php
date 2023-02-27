<?php

namespace App\Repositories;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;

class ProviderInvoiceRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id'
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
        return Invoice::class;
    }

    public function invoiceView($request){
        if( $request->uuid != '' ){
            return  Invoice::where('uuid',$request->uuid)
                    ->with('invoiceItems.files.file')
                    ->with('booking.job.service','booking.job.equipments.equipment.file')
                    ->with('booking.job.company.location.state')
                    ->with('booking.provider.documents.document','booking.provider.user')
                    ->first();

            $invoice    =   (new Invoice)->invoiceView($request->uuid);
            return array('message' => __('messages.success'), 'status'=>True,'statusCode' => 200, 'data' => $invoice );

        }else{
            return array('message' => __('messages.not_found_entry'), 'status'=>False, 'statusCode'=> 400, 'data' => [] );
        }
    }

}
