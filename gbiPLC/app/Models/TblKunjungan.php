<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TblKunjungan extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'tbl_kunjungans';

    protected $fillable = [
        'id_jemaat',
        'tglkunjungan',
        'nama_timbesuk',
        'filename',
        'path',
        'keterangan'
    ];

    public function jemaats()
    {
        return $this->belongsTo(User::class,'id_jemaat');
    }

    
}