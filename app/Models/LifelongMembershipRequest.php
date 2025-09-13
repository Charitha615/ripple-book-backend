<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifelongMembershipRequest extends Model
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
        'whatsapp_number',
        'email_address',

        // Additional Address
        'residential_address',

        // Additional Information
        'queries',

        // System Fields
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
