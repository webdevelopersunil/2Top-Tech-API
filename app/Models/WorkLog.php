<?php

namespace App\Models;

use App\Http\Service\TimeService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkLog extends Model
{
    use HasFactory,SoftDeletes;

    protected $table   =    'work_logs';

    protected $fillable = [
        'uuid',
        'booking_id',
        'comment',
        'completion_status',
        'type',
        'log_time',
        'interval_time'
    ];

    protected $hidden = [
        'id'
    ];

    public function booking(){
        return $this->belongsTo(JobBooking::class, 'booking_id','id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function createWorkLog($type,$comment,$booking_id,$intervalTime,$completionStatus){

        $workLog                    =   new WorkLog;
        $workLog->type              =   $type;
        $workLog->completion_status =   $completionStatus;
        $workLog->comment           =   $comment;
        $workLog->booking_id        =   $booking_id;
        $workLog->log_time          =   (new TimeService)->currentTime();
        $workLog->interval_time     =   $intervalTime;

        $workLog->save();
    }

    public function checkIfWorkLog($booking_id){

        $foundIfAny = Self::where('booking_id',$booking_id)->where('type','WorkLog')->first();
        return $foundIfAny;
    }

    public function getWorkLogs($booking_id, $status){

        return  self::where(['booking_id'=>$booking_id])
                ->when(count($status) >= 1, function ($q) use ($status) {
                    $q->whereIn('type', $status);
                })
                ->orderBy('created_at','ASC')
                ->get(['uuid','comment','interval_time','log_time','type']);
    }
}
