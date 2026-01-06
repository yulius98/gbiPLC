<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class reading_schedules extends Model
{
    use HasFactory;

    protected $table = 'reading_schedules';

    protected $fillable = [
        'day',
        'morning_passage',
        'evening_passage',
    ];
}
