<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Media;

class Service extends Model implements  HasMedia
{
    use InteractsWithMedia,HasFactory,SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'name',
        'description',
        'order',
        'status'
    ];

    protected $hidden = [

        'status',
        'order'
    ];

    public function providers(){
        return $this->belongsTo('App\Models\User','provider_id','id')->withTrashed();
    }

    public function scopeMyService($query)
    {
        if(auth()->user()->hasRole('admin')) {
            return $query;
        }

        if(auth()->user()->hasRole('provider')) {
            return $query->where('provider_id', \Auth::id());
        }

        return $query;
    }

    public function providerService(){
        return $this->belongsTo(ProviderService::class, 'id', 'service_id');
    }

    public function mediaFile(){
        return $this->hasOne(Media::class, 'model_id', 'id');
    }

    public function getServices(){

        $services   =   self::with('mediaFile')->get();
        return $services;
    }
}
