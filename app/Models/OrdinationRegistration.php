<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdinationRegistration extends Model
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
        'marital_status',
        'has_permission',

        // Background Check
        'military_service',
        'criminal_record',

        // Ordination Details
        'ordination_type',
        'ordination_time',
        'ordination_month',
        'ordination_year',

        // Emergency Contact
        'emergency_first_name',
        'emergency_last_name',
        'emergency_email',
        'emergency_relationship',
        'emergency_mobile_1',
        'emergency_mobile_2',

        // Medical History
        'has_mental_disorder_history',
        'has_contagious_disease',
        'other_health_complications',

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
        'has_permission' => 'boolean',
        'military_service' => 'boolean',
        'criminal_record' => 'boolean',
        'has_mental_disorder_history' => 'boolean',
        'has_contagious_disease' => 'boolean',
        'ordination_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
