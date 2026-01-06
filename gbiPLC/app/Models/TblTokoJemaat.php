<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblTokoJemaat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_toko_jemaats';

    protected $fillable =[
        'nama',
        'nama_usaha',
        'jenis_usaha',
        'alamat_usaha',
        'no_telp',
        'keterangan'
    ];
}