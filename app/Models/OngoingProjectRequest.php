<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OngoingProjectRequest extends Model
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

        // Project Details
        'work_types',
        'number_of_volunteers',
        'preferred_time',
        'requires_accommodation',

        // System Fields
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'number_of_volunteers' => 'integer',
        'requires_accommodation' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
