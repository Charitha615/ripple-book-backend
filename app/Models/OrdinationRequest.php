<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdinationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        // Personal Information
        'first_name',
        'last_name',
        'date_of_birth',
        'age',
        'gender',
        'address',
        'city',
        'postal_code',
        'mobile_number',
        'whatsapp_number',
        'email_address',

        // Ordination Details
        'ordination_type',
        'ordination_month',
        'ordination_year',

        // Additional Information
        'queries',

        // System Fields
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'age' => 'integer',
        'ordination_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
