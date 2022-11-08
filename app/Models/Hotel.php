<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HotelAuthority;

class Hotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name_en',
        'name_bn',
        'star_level',
        'police_station_id',
        'address',
        'hotel_phone_number',
        'hotel_email',
        'website',
        'estd',
        'facebook',
        'other_social_id',
        'hotel_license_no',
        'hotel_license_reg_date',
        'trade_license_no',
        'tin_no',
        'vat_no',
        'bin_no',
        'environment_certificate',
        'fireservice_certificate',
        'manager',
        'description_of_foreign_investment',
        'no_of_room',
        'no_of_officer',
        'no_of_employee',
        'no_of_cc_camera',
        'parking',
        'emergency_exit',
        'firefighting_system',
        'last_date_of_firefighting_ex',
        'generator',
        'owners_asso_membership',
        'review',
        'other_info',
        'remark',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->select([
            'id',
            'email',
            'phone',
        ]);
    }

    public function authorities()
    {
        return  $this->hasMany('App\Models\HotelAuthority')->select([
            'id',
            'hotel_id',
            'name',
            'email',
            'phone',
            'address',
            'city',
            'nid',
            'political_identity',
            'position'
        ]);
    }

    public function services()
    {
        return $this->hasOne('App\Models\HotelService', 'hotel_id')->select([
            'id',
            'hotel_id',
            'restaurant',
            'bar',
            'gym',
            'swimming_pool',
            'conference_hall',
            'massage_center',

        ]);
    }
    public function foreignEmployees()
    {
        return  $this->hasMany('App\Models\ForeignEmployee')->select([
            'id',
            'hotel_id',
            'name',
            'address',
            'city',
            'phone_number',
            'passport',
            'passport_issue_date',
            'passport_exp_date',
            'visa_type',
            'security_clearance'
        ]);
    }



    public function bookings()
    {
        return  $this->hasMany('App\Models\Booking')->with('guests')->select(['id', 'hotel_id', 'booking_no']);
    }

    public function guests()
    {
        return  $this->hasMany('App\Models\HotelGuest')->with('hotel', 'room')->select([
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
    public function guests2()
    {
        return  $this->hasMany('App\Models\HotelGuest')->with('room')->select([
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

    public function rooms()
    {
        return $this->hasMany('App\Models\Room')->select([
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

    public function police_station()
    {
        return $this->belongsTo('App\Models\PoliceStation', 'police_station_id')->select([
            'id',
            'user_id',
            'name',
            'district'
        ]);
    }
}
