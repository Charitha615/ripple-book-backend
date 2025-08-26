<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KatinaCeremonyRequestForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'katina_ceremony_request_forms';

    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'wt_number',
        'email',
        'year',
        'ip_address',
        'status',
        'status_reason'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
