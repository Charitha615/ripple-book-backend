<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalRetreat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'request_for_retreat',
        'retreat_no',
        'course_type',
        'start_date',
        'end_date',
        'status',
        'teachers_name',
        'organiser_contact_no',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
