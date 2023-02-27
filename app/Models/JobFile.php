<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'job_id',
        'file_id',
    ];

    protected $hidden = [
        'id',
        'job_id',
    ];

    public function fileDetail(){

        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    public function saveJobFiles($files_id,$job_id){

        if(isset($files_id)){

            $filesArr   =   explode( ',' , trim($files_id) );
            $jobFile    =   new self;

            $res=$jobFile->where('job_id',$job_id)->whereNotIn('file_id', $filesArr)->delete();

            foreach( $filesArr as $file_id ){
                $jobFile->updateOrCreate(['job_id'=>$job_id,'file_id'=>$file_id],['job_id'=>$job_id,'file_id'=>$file_id]);
            }
        }
    }
}
