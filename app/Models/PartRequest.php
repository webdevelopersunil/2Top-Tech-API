<?php

namespace App\Models;

use App\Models\File;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @SWG\Definition(
 *      definition="PartRequest",
 *      required={"provider_id", "file_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="provider_id",
 *          description="provider_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="equipment_id",
 *          description="equipment_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="sku",
 *          description="sku",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="file_id",
 *          description="file_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="make",
 *          description="make",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="model_no",
 *          description="model_no",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="sn_number",
 *          description="sn_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="location",
 *          description="location",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="refrigerant_type",
 *          description="refrigerant_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="warranty_Info",
 *          description="warranty_Info",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="voltage_amps",
 *          description="voltage_amps",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="date_of_purchase",
 *          description="date_of_purchase",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="filter_number",
 *          description="filter_number",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="comment",
 *          description="comment",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class PartRequest extends Model
{
    use SoftDeletes;


    public $table = 'part_requests';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'uuid',
        'provider_id',
        'equipment_id',
        'name',
        'description',
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'provider_id' => 'integer',
        'equipment_id' => 'string',
        'name' => 'string',
        'sku' => 'string',
        'file_id' => 'integer',
        'make' => 'string',
        'model_no' => 'string',
        'sn_number' => 'string',
        'location' => 'string',
        'refrigerant_type' => 'string',
        'warranty_Info' => 'string',
        'voltage_amps' => 'string',
        'date_of_purchase' => 'datetime',
        'filter_number' => 'string',
        'comment' => 'string',
        'status' => 'string'
    ];

    protected $hidden = [
        'id'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'provider_id' => 'nullable',
        'file_id' => 'required'
    ];

    public function file(){
        return $this->belongsTo(File::class,'file_id','id');
    }

    public function provider(){
        return $this->belongsTo(Provider::class,'provider_id','id');
    }
}
