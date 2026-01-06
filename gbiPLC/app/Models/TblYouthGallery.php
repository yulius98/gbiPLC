<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TblYouthGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'file_path',
        'thumbnail_path',
        'category',
        'event_date',
        'is_featured',
        'order'
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_featured' => 'boolean',
    ];

    // Accessor untuk URL file
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path);
    }

    // Accessor untuk URL thumbnail
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? Storage::url($this->thumbnail_path) : null;
    }

    // Scope untuk featured gallery
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope untuk tipe
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    // Scope untuk urutan
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('event_date', 'desc');
    }
}



