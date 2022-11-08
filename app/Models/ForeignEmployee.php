<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForeignEmployee extends Model
{
    use HasFactory;
    protected $fillable = [
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

    ];
}
