<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingRating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'booking_id',
        'rating_type',
        'rating_comment',
        'rate',
        'rating_by'
    ];

    protected $hidden   =   [
        'id',
        'booking_id',
        'rating_type',
    ];

    /**
         * Validation rules
         *
         * @var array
         */
    public static $rules = [

    ];

    protected $casts = [
        'booking_id'    => 'integer',
        'rating_by'    => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id', 'booking_id');
    }

    public function ratingby()
    {
        return $this->belongsTo(User::class, 'rating_by', 'id')->with('company');
    }

    public function scopeMyRating($query){
        $user = auth()->user();
        if($user->hasRole('admin') || $user->hasRole('demo_admin')) {
            $query =  $query;
        }

        if($user->hasRole('provider')) {
            $query = $query->whereHas('service',function ($q) use($user) {
                $q->where('provider_id',$user->id);
            });
        }

        return  $query;
    }

    public function calculateAverageRating($data){

        $booking    =   JobBooking::where('id',$data['booking_id'])->first();

        if( $data['rating_type'] == 'company' ){

            $provider_id=   $booking->provider_id;

            $rating =  JobBooking::join('ratings','ratings.booking_id','=','job_bookings.id')
                        ->where('job_bookings.provider_id','=',$provider_id)
                        ->select(
                            DB::raw('SUM(ratings.rate) as total'),
                            DB::raw('COUNT(ratings.rate) as count')
                        )->first();

            $average = $rating->total / $rating->count;

            if( $average != null && $average != 0 ){
                Provider::where('id',$provider_id)->update([ 'avg_rating' => $average ]);
            }

        }elseif( $data['rating_type'] == 'provider' ){

        }
    }
}
