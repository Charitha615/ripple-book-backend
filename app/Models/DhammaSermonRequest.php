<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DhammaSermonRequest extends Model
{
    use HasFactory;

    protected $table = 'dhamma_sermon_requests';

    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'whatsapp_number',
        'email_address',
        'date',
        'time',
        'sanga_members_count',
        'seven_day',
        'three_day',
        'one_year',
        'annually',
        'birthday',
        'house_warming',
        'weddings_anniversary',
        'other_event',
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'seven_day' => 'boolean',
        'three_day' => 'boolean',
        'one_year' => 'boolean',
        'annually' => 'boolean',
        'birthday' => 'boolean',
        'house_warming' => 'boolean',
        'weddings_anniversary' => 'boolean',
        'date' => 'date',
    ];
}
