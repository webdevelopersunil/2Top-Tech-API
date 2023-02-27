<?php

namespace App\Models;

// use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


/**
 * Class EmailTemplate
 * @package App\Models
 * @version December 2, 2022, 8:18 am UTC
 *
 * @property string $name
 * @property string $email_subject
 * @property string $email_body
 */
class EmailTemplate extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'email_templates';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'email_subject',
        'email_body'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'email_subject' => 'string',
        'email_body' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
