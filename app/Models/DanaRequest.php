<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DanaRequest extends Model
{
    use HasFactory , SoftDeletes;


    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'wt_number',
        'email',
        'dana_event_date',
        'ip_address',
        'status',
        'status_reason',
        'is_breakfast',
        'is_lunch'
    ];

    protected $dates = ['deleted_at'];
}
