<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalRetreatRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'retreat_no',
        'internal_retreat_id',
        'first_name',
        'last_name',
        'mobile_number',
        'whatsapp_number',
        'email_address',
        'gender',
        'interested_retreat_number',
        'preferred_dates',
        'queries',
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'preferred_dates' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function internalRetreat(): BelongsTo
    {
        return $this->belongsTo(InternalRetreat::class);
    }
}
