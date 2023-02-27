<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CompanyLocation extends Model implements  HasMedia
{

    use InteractsWithMedia, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'company_id',
        'restaurant_name',
        'contact_name',
        'email',
        'address',
        'city',
        'state_id',
        'latitude',
        'longitude',
        'phone_number',
        'company_cusine_id',
        'restaurant_type',
        'seats',
        'bar',
        'parking'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'company_id'
    ];

    public function states(){
        return $this->hasMany(State::class, 'id','state_id');
    }

    public function state(){
        return $this->hasOne(State::class, 'id','state_id');
    }


}
