<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Contracts\Service\Attribute\Required;

class SlotBooking extends Model
{
    use HasFactory;

    protected $fillable = ['slot_id', 'service_type_id', 'customer_email', 'customer_first_name', 'customer_last_name', 'customer_gender']; 

    protected $hidden = ['created_at', 'updated_at'];

    public static function rules() {
        return [
            'date' => 'required|date_format:Y-m-d',
            'service_type_id' => 'required|integer|exists:service_types,id',
            'customer_email' => 'required|email',
            'customer_first_name' => 'required|string|max:50',
            'customer_last_name' => 'required|string|max:50',
            'customer_gender' => 'required|in:male,female'
        ];
    }
}
