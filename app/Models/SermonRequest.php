<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SermonRequest extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'wt_number',
        'email',
        'date',
        'time',
        'count',
        'option',
        'birthday',
        'sevenday',
        'warming',
        'threemonths',
        'oneyear',
        'annually',
        'weddings',
        'ip_address', // Include the IP address field
    ];

    protected $dates = ['deleted_at'];
}
