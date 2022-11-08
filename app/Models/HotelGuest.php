<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelGuest extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'hotel_id',
        'booking_id',
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
        'occupation',
        'other_info',
        'guest_img_path',
        'nid_img_path',
    ];


    public function room()
    {
        return $this->belongsTo('App\Models\Room', 'room_id')->select([
            'id',
            'hotel_id',
            'room_name',
            'floor_no',
            'number_of_bed',
            'has_ac',
            'other_info',
            'room_type',
        ]);
    }
    public function booking()
    {
        return $this->belongsTo('App\Models\Booking', 'room_id')->select([
            'id',
            'booking_no'
        ])->with('guests');
    }
    public function hotel()
    {
        return $this->belongsTo('App\Models\Hotel', 'hotel_id')
            ->leftJoin('police_stations', 'police_stations.id', 'hotels.police_station_id')
            ->select([
                'hotels.*', 'police_stations.name as police_station', 'police_stations.district as district'
            ]);
    }
}
