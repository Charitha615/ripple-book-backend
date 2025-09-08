<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DhammaTalk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dhamma_talks';

    protected $fillable = [
        'sutta_title',
        'event_title',
        'retreat_no',
        'event_location',
        'event_start_date',
        'event_end_date',
        'links'
    ];

    protected $casts = [
        'event_start_date' => 'date',
        'event_end_date' => 'date',
        'links' => 'array',
    ];

    // Scope for searching
    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('sutta_title', 'like', "%{$searchTerm}%")
                ->orWhere('event_title', 'like', "%{$searchTerm}%")
                ->orWhere('retreat_no', 'like', "%{$searchTerm}%")
                ->orWhere('event_location', 'like', "%{$searchTerm}%");
        });
    }

    // Scope for filtering by date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('event_start_date', [$startDate, $endDate])
            ->orWhereBetween('event_end_date', [$startDate, $endDate]);
    }

    // Scope for filtering by retreat number
    public function scopeByRetreatNo($query, $retreatNo)
    {
        return $query->where('retreat_no', 'like', "%{$retreatNo}%");
    }

    // Scope for filtering by location
    public function scopeByLocation($query, $location)
    {
        return $query->where('event_location', 'like', "%{$location}%");
    }
}
