<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblCarousel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_carousels';

    protected $fillable = [
        'tema',
        'description',
        'filename',
        'path'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->filename ? asset('storage/' . $this->filename) : null;
    }

    /**
     * Scope untuk carousel aktif (tidak dihapus)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
