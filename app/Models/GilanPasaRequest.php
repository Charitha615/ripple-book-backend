<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GilanPasaRequest extends Model
{
    use HasFactory , SoftDeletes;


    protected $fillable = [
        'first_name',
        'last_name',
        'mobile_number',
        'wt_number',
        'email',
        'date',
        'ip_address',
    ];

    protected $dates = ['deleted_at'];
}
