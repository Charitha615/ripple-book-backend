<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLog extends Model
{
    use SoftDeletes, HasFactory ;// Enables soft deletes

    protected $fillable = [
        'user_id',
        'form_id',
        'user_name',
        'user_role',
        'action_type',
        'action_date_time',
        'entity_area',
        'old_values',
        'new_values',
        'description',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
        'action_date_time' => 'datetime',
    ];
}
