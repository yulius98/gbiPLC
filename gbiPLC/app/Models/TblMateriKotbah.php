<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblMateriKotbah extends Model
{
    protected $table = 'tbl_materi_kotbahs';
    protected $fillable =[
        'tgl_kotbah',
        'judul',
        'filename',
        'path'
    ];

}
