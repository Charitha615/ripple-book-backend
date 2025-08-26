<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestSpeakerRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guest_speaker_requests';

    protected $fillable = [
        'organiser_full_name',
        'organiser_gender',
        'organiser_mobile_number',
        'organiser_whatsapp_number',
        'organiser_email_address',
        'guest_full_name',
        'guest_first_name',
        'guest_last_name',
        'guest_date_of_birth',
        'guest_gender',
        'guest_email_address',
        'aranya_temple_name',
        'country_of_residence',
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'guest_date_of_birth' => 'date',
        'organiser_gender' => 'string',
        'guest_gender' => 'string',
    ];

    protected $attributes = [
        'status' => 'Pending',
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

    // Accessor for guest full name (if not provided)
    public function getGuestFullNameAttribute($value)
    {
        if (!$value) {
            return $this->guest_first_name . ' ' . $this->guest_last_name;
        }
        return $value;
    }

    // Mutator for guest full name
    public function setGuestFullNameAttribute($value)
    {
        $this->attributes['guest_full_name'] = $value;

        // If first and last names are empty, try to split the full name
        if (empty($this->guest_first_name) && empty($this->guest_last_name)) {
            $names = explode(' ', $value, 2);
            $this->attributes['guest_first_name'] = $names[0] ?? '';
            $this->attributes['guest_last_name'] = $names[1] ?? '';
        }
    }
}
