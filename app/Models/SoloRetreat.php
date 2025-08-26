<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoloRetreat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'solo_retreats';

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
        'number_of_days',
        'solo_retreatant_clarification',
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_beginner' => 'boolean',
        'is_experienced' => 'boolean',
        'number_of_days' => 'integer',
        'age' => 'integer'
    ];

    protected $attributes = [
        'status' => 'Pending',
        'is_beginner' => false,
        'is_experienced' => false,
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

    // Accessor for full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
