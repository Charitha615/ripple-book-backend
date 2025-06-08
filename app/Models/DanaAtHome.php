<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanaAtHome extends Model
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
        'specific_event',
        'other',
        'birthday',
        'sevenday',
        'warming',
        'threemonths',
        'oneyear',
        'annually',
        'weddings',
        'ip_address',
        'status',
        'status_reason',
    ];

    protected $dates = ['deleted_at'];
}
