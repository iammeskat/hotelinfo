<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelService extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_id',
        'restaurant',
        'bar',
        'gym',
        'swimming_pool',
        'conference_hall',
        'massage_center',

    ];
}
