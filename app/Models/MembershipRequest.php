<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipRequest extends Model
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

        // Donation Details
        'donation_purpose',
        'other_purpose',
        'donation_type',
        'payment_method',

        // Signature and Date
        'signature',
        'application_date',

        // System Fields
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'application_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
