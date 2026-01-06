<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TblEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_events';

    protected $fillable = [
        'tgl_event',
        'keterangan',
        'filename',
        'path'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tgl_event' => 'date',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get photo URL for API response
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->filename ? asset('storage/' . $this->filename) : null;
    }

    /**
     * Scope untuk event bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tgl_event', Carbon::now()->month);
    }

    /**
     * Scope untuk event mendatang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('tgl_event', '>=', Carbon::today());
    }

    /**
     * Scope untuk event yang sudah lewat
     */
    public function scopePast($query)
    {
        return $query->where('tgl_event', '<', Carbon::today());
    }

    /**
     * Get formatted event date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->tgl_event->format('d M Y');
    }
}
