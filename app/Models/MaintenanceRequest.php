<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        // Personal Information
        'first_name',
        'last_name',

        // Address Information
        'address',
        'city',
        'postal_code',

        // Contact Information
        'mobile_number',
        'phone_with_country_code',
        'email_address',

        // Maintenance Details
        'maintenance_type',
        'number_of_volunteers',
        'preferred_time',

        // System Fields
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'number_of_volunteers' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
