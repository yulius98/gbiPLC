<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblIbadahRaya extends Model
{
    protected $table = 'tbl_ibadah_rayas';
    protected $fillable = [
        'tgl_ibadah',
        'ibadah_ke',
        'link_ibadah',
    ];
}
