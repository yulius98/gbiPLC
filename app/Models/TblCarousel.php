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
        'filename',
        'path'
    ];
}