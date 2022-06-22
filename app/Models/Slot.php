<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'start_time', 'end_time', 'max_customer', 'booked_seats', 'available_male_seats', 'available_female_seats', 'is_fully_booked'];

    protected $hidden = ['created_at', 'updated_at'];

    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
}
