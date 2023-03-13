<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ToDo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table        =   "todo";
    protected $primaryKey   =   "id";
    protected $fillable     =   ['uuid','name','user_id','service_id','status','due_date','comment'];
    protected $hidden       =   ['id','service_id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function service(){
        return $this->belongsTo(Service::class,'service_id', 'id');
    }

    public function store($request,$user_id){

        $todo   =   new ToDo();
        $todo->name =   $request['name'];
        $todo->user_id  =   $user_id;
        $todo->service_id   =   $request['service_id'];
        $todo->due_date     =   $request['due_date'];
        $todo->comment      =   $request['comment'];
        $todo->save();

        return $todo;
    }
}
