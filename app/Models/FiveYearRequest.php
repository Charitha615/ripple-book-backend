<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FiveYearRequest extends Model
{
    use HasFactory , SoftDeletes;


    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'street_address_line_1',
        'street_address_line_2',
        'city',
        'postal_code',
        'country',
        'mobile_number',
        'wt_number',
        'email',
        '5_land_plots',
        '10_land_plots',
        '20_land_plots',
        '50_land_plots',
        'query',
        'ip_address',
    ];

    protected $dates = ['deleted_at'];
}
