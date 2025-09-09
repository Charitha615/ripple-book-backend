<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paritta_at_Home_Request_Form extends Model
{
    use HasFactory;

    protected $table = 'paritta_at__home__request__forms';

    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'whatsapp_number',
        'email_address',
        'date',
        'time',
        'sanga_members_count',
        'birthday',
        'new_business',
        'house_warming',
        'sick_in_need',
        'exams',
        'wedding_anniversary',
        'weddings',
        'pregnant_mums',
        'new_born',
        'other_event',
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'birthday' => 'boolean',
        'new_business' => 'boolean',
        'house_warming' => 'boolean',
        'sick_in_need' => 'boolean',
        'exams' => 'boolean',
        'wedding_anniversary' => 'boolean',
        'weddings' => 'boolean',
        'pregnant_mums' => 'boolean',
        'new_born' => 'boolean',
        'date' => 'date',
    ];
}
