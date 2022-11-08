<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_id',
        'room_name',
        'floor_no',
        'number_of_bed',
        'has_ac',
        'other_info',
        'room_type',
        'status',
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
            'nid_no',
            'dob',
            'nationality',
            'place_of_birth',
            'arrival_date',
            'leaving_date',
            'length_of_stay',
            'room_id',
            'other_info',
            'guest_img_path',
            'nid_img_path'
        ]);
    }
}
