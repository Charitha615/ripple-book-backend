<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalRetreatRequestForm_GlenWaverley extends Model
{
    use HasFactory , SoftDeletes;


    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'wt_number',
        'email',
        'number_of_people',
        'ip_address',
        'status',
        'status_reason',
    ];

    protected $dates = ['deleted_at'];
}
