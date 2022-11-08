<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoliceStation extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'district',
    ];

    public function hotels()
    {
        return  $this->hasMany('App\Models\Hotel')
            ->leftJoin('users', 'users.id', '=', 'hotels.user_id')
            ->select([
                'hotels.*', 'users.phone', 'users.email'
            ]);
    }
}
