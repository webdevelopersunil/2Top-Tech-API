<?php

namespace App\Models;

use Customers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Cuisine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function RestaurantCuisine(){
        return $this->belongsTo(RestaurantCuisine::class, 'id', 'cuisine_id');
    }

}
