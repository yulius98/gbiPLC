<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TblYouthSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'day_of_week',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'location_url',
        'category',
        'is_active',
        'order'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    // Accessor untuk format waktu
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('H:i') . ' - ' . 
               Carbon::parse($this->end_time)->format('H:i');
    }

    // Scope untuk schedule aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk weekly schedule
    public function scopeWeekly($query)
    {
        return $query->where('type', 'weekly');
    }

    // Scope untuk special events
    public function scopeSpecialEvents($query)
    {
        return $query->where('type', 'special_event')
                    ->where('event_date', '>=', now())
                    ->orderBy('event_date', 'asc');
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

}
