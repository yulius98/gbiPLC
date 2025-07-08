<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblEvent extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'tbl_events';

    protected $fillable =[
        'tgl_event',
        'keterangan',
        'filename',
        'path'
    ];
}