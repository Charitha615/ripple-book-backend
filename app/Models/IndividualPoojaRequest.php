<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualPoojaRequest extends Model
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

        // Pooja Purpose
        'for_birthday',
        'for_wedding_anniversary',
        'for_punyanumoda',
        'for_other',
        'other_purpose',

        // Additional Information
        'queries',

        // System Fields
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'for_birthday' => 'boolean',
        'for_wedding_anniversary' => 'boolean',
        'for_punyanumoda' => 'boolean',
        'for_other' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Helper method to get all selected purposes
    public function getSelectedPurposesAttribute()
    {
        $purposes = [];

        if ($this->for_birthday) $purposes[] = 'Birthday';
        if ($this->for_wedding_anniversary) $purposes[] = 'Wedding Anniversary';
        if ($this->for_punyanumoda) $purposes[] = 'Punyanumoda (Diseased)';
        if ($this->for_other && $this->other_purpose) $purposes[] = 'Other: ' . $this->other_purpose;

        return implode(', ', $purposes);
    }
}
