<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditUserLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
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
}
