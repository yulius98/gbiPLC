<?php

namespace Database\Seeders;

use App\Models\TblYouthProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Ibadah Youth',
                'description' => 'Ibadah khusus youth dengan worship energik dan pengajaran yang relevan',
                'frequency' => 'Setiap Sabtu',
                'category' => 'Worship',
                'order' => 1
            ],
            [
                'title' => 'Small Group',
                'description' => 'Kelompok kecil untuk sharing dan belajar Alkitab',
                'frequency' => 'Mingguan',
                'category' => 'Fellowship',
                'order' => 2
            ],
            
        ];

        foreach ($programs as $program) {
            TblYouthProgram::create($program);
        }
    }

}

