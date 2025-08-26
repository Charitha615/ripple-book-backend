<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalRetreatOrganiserRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'internal_retreat_organiser_registrations';

    protected $fillable = [
        'first_name',
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
        'emergency_first_name',
        'emergency_last_name',
        'emergency_relationship',
        'emergency_mobile_number_1',
        'emergency_mobile_number_2',
        'beginner',
        'experienced_volunteer',
        'months_experience',
        'years_experience',
        'months',
        'description',
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'beginner' => 'boolean',
        'experienced_volunteer' => 'boolean',
        'months_experience' => 'boolean',
        'years_experience' => 'boolean',
        'deleted_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date_of_birth', 'deleted_at'];
}
