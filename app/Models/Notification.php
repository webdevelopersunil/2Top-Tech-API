<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'title',
        'description',
        'status',
        'url',
        'read_at'
    ];

    protected $hidden = [
        'id',
        'notifiable_id'
    ];
}
