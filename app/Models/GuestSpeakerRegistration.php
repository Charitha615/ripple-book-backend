<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestSpeakerRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        // Organiser Details
        'organiser_full_name',
        'organiser_dob',
        'organiser_age',
        'organiser_mobile_number',
        'organiser_whatsapp_number',
        'organiser_email',

        // Guest Speaker Details
        'speaker_first_name',
        'speaker_last_name',
        'speaker_dob',
        'speaker_age',
        'speaker_gender',
        'speaker_type',
        'vassa_years',
        'samanera_years',
        'nun_years',

        // Residence Details
        'monastery_name',
        'country_of_residence',
        'address',
        'city',
        'postal_code',
        'country',
        'speaker_mobile_number',
        'speaker_phone_with_country_code',
        'speaker_email',

        // Experience Details
        'experience_level',
        'retreat_experience_value',
        'retreat_experience_unit',

        // Retreat Program Details
        'retreat_duration',
        'preferred_days',
        'preferred_month',
        'preferred_year',
        'expected_participants',

        // Additional Information
        'queries',

        // System Fields
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'organiser_dob' => 'date',
        'organiser_age' => 'integer',
        'speaker_dob' => 'date',
        'speaker_age' => 'integer',
        'vassa_years' => 'integer',
        'samanera_years' => 'integer',
        'nun_years' => 'integer',
        'retreat_experience_value' => 'integer',
        'expected_participants' => 'integer',
        'preferred_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
