<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [

        'hotel_id',
        'reservation_no',
        'booking_source',
        'arrival_date',
        'departure_date',
    ];

    public function guests()
    {
        return  $this->hasMany('App\Models\HotelGuest')->select([
            'id',
            'hotel_id',
            'booking_id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'dob',
            'nationality',
            'place_of_birth',
            'arrival_date',
            'length_of_stay',
            'room_id',
            'other_info',
            'guest_img_path',
            'nid_img_path'
        ]);
    }
}
