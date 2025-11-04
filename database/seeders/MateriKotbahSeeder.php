<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TblMateriKotbah;
use Carbon\Carbon;

class MateriKotbahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data untuk materi kotbah
        $materiKotbah = [
            [
                'tgl_kotbah' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'judul' => 'Kasih yang Sempurna',
                'filename' => null, // Tidak ada file untuk contoh ini
                'path' => null,
            ],
            [
                'tgl_kotbah' => Carbon::now()->subDays(14)->format('Y-m-d'),
                'judul' => 'Iman yang Teguh dalam Pencobaan',
                'filename' => null,
                'path' => null,
            ],
            [
                'tgl_kotbah' => Carbon::now()->subDays(21)->format('Y-m-d'),
                'judul' => 'Pengharapan yang Hidup',
                'filename' => null,
                'path' => null,
            ],
            [
                'tgl_kotbah' => Carbon::now()->format('Y-m-d'),
                'judul' => 'Berkat Tuhan yang Melimpah',
                'filename' => null,
                'path' => null,
            ],
        ];

        foreach ($materiKotbah as $materi) {
            TblMateriKotbah::create($materi);
        }
    }
}