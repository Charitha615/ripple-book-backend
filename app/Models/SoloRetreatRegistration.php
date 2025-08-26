<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoloRetreatRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'solo_retreat_registrations';

    protected $fillable = [
        'full_name',
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
        'is_beginner',
        'is_experienced',
        'from_meditation_teacher',
        'number_of_days',
        'arrival_date',
        'departure_date',
        'emergency_first_name',
        'emergency_last_name',
        'emergency_relationship',
        'emergency_mobile_number_1',
        'emergency_mobile_number_2',
        'has_schizophrenia_or_manic_depression',
        'has_chronic_illness',
        'health_complications',
        'specific_questions',
        'pdf_upload',
        'sign_full_name',
        'sign_date',
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'arrival_date' => 'date',
        'departure_date' => 'date',
        'sign_date' => 'date',
        'is_beginner' => 'boolean',
        'is_experienced' => 'boolean',
        'from_meditation_teacher' => 'boolean',
        'has_schizophrenia_or_manic_depression' => 'boolean',
        'has_chronic_illness' => 'boolean',
        'number_of_days' => 'integer',
        'age' => 'integer'
    ];

    protected $attributes = [
        'status' => 'Pending',
        'is_beginner' => false,
        'is_experienced' => false,
        'from_meditation_teacher' => false,
        'has_schizophrenia_or_manic_depression' => false,
        'has_chronic_illness' => false,
    ];

    // Scopes for status filtering
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    public function scopeOnHold($query)
    {
        return $query->where('status', 'On hold');
    }
}
