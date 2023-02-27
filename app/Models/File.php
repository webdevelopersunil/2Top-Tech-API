<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'extension',
        'type',
        'size'
    ];

    public function fileDetail()
    {
        return $this->belongsTo(File::class, 'logo_file_id', 'id');
    }

}
