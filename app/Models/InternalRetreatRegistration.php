<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalRetreatRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_code',
        'first_name',
        'last_name',
        'age',
        'religion',
        'gender',
        'address',
        'street_address',
        'street_address_line_2',
        'city',
        'postal_code',
        'mobile_number',
        'whatsapp_number',
        'email_address',
        'is_experienced_meditator',

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

        // Retreat Attendance
        'retreat_no',
        'attend_full_retreat',
        'preferred_roommate_name',
        'number_of_days',
        'arrival_date',
        'departure_date',
        'monastic_status',

        // PDF
        'pdf_base64',
        'pdf_filename',

        // Declaration
        'declaration_full_name',
        'declaration_date',

        // System
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'is_experienced_meditator' => 'boolean',
        'has_mental_disorder_history' => 'boolean',
        'has_contagious_disease' => 'boolean',
        'attend_full_retreat' => 'boolean',
        'age' => 'integer',
        'number_of_days' => 'integer',
        'arrival_date' => 'date',
        'departure_date' => 'date',
        'declaration_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
