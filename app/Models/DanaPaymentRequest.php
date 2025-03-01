<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanaPaymentRequest extends Model
{
    use HasFactory , SoftDeletes;


    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'wt_number',
        'email',
        'dana_for_morning',
        'dana_for_lunch',
        'dana_event_date',
        'ip_address', // Include the IP address field
    ];

    protected $dates = ['deleted_at'];
}
