<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandLotRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        // Personal Information
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',

        // Address Information
        'address',
        'street_address',
        'street_address_line_2',
        'city',
        'postal_code',
        'country',

        // Contact Information
        'whatsapp_number',
        'mobile_number',
        'email_address',

        // Land Lot Details
        'land_lot_numbers',

        // Additional Information
        'queries',

        // System Fields
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'land_lot_numbers' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
