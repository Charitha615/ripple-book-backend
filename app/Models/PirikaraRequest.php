<?php
// app/Models/PirikaraRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PirikaraRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'whatsapp_number',
        'email_address',
        'pirikara_type',
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'pirikara_type' => 'array', // If storing as JSON
    ];
}
