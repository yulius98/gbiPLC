<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblPastorNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_pastor_notes';
    protected $fillable =[
        'tgl_note',
        'note',
        'filename',
        'path'
    ];
}