<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelAuthority extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'nid',
        'political_identity',
        'position'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id')->select(['id', 'email', 'phone']);
    }
}
