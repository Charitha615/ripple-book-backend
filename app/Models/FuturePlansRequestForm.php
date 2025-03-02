<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuturePlansRequestForm extends Model
{
    use HasFactory , SoftDeletes;


    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'city',
        'postal_code',
        'mobile_number',
        'wt_number',
        'email',
        'contribute',
        'inquire',
        'ip_address',
    ];

    protected $dates = ['deleted_at'];
}
