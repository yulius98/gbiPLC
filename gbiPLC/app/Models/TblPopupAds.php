<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblPopupAds extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tbl_popup_ads';
    protected $fillable = [
        'filename',
        'path'
    ];
}
