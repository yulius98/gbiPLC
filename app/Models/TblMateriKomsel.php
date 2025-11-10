<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblMateriKomsel extends Model
{
    use HasFactory;
    protected $table = 'tbl_materi_komsels';
    protected $fillable =[
        'tgl_komsel',
        'judul',
        'filename',
        'path'
    ];
}
