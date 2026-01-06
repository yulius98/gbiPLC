<?php

namespace Database\Seeders;

use App\Models\reading_schedules;
use App\Models\ReadingSchedule;
use Illuminate\Database\Seeder;

class ReadingScheduleSeeder extends Seeder
{
    private $books = [
        ['usfm' => 'GEN', 'chapters' => 50], ['usfm' => 'EXO', 'chapters' => 40],
        ['usfm' => 'LEV', 'chapters' => 27], ['usfm' => 'NUM', 'chapters' => 36],
        ['usfm' => 'DEU', 'chapters' => 34], ['usfm' => 'JOS', 'chapters' => 24],
        ['usfm' => 'JDG', 'chapters' => 21], ['usfm' => 'RUT', 'chapters' => 4],
        ['usfm' => '1SA', 'chapters' => 31], ['usfm' => '2SA', 'chapters' => 24],
        ['usfm' => '1KI', 'chapters' => 22], ['usfm' => '2KI', 'chapters' => 25],
        ['usfm' => '1CH', 'chapters' => 29], ['usfm' => '2CH', 'chapters' => 36],
        ['usfm' => 'EZR', 'chapters' => 10], ['usfm' => 'NEH', 'chapters' => 13],
        ['usfm' => 'EST', 'chapters' => 10], ['usfm' => 'JOB', 'chapters' => 42],
        ['usfm' => 'PSA', 'chapters' => 150], ['usfm' => 'PRO', 'chapters' => 31],
        ['usfm' => 'ECC', 'chapters' => 12], ['usfm' => 'SNG', 'chapters' => 8],
        ['usfm' => 'ISA', 'chapters' => 66], ['usfm' => 'JER', 'chapters' => 52],
        ['usfm' => 'LAM', 'chapters' => 5], ['usfm' => 'EZK', 'chapters' => 48],
        ['usfm' => 'DAN', 'chapters' => 12], ['usfm' => 'HOS', 'chapters' => 14],
        ['usfm' => 'JOL', 'chapters' => 3], ['usfm' => 'AMO', 'chapters' => 9],
        ['usfm' => 'OBA', 'chapters' => 1], ['usfm' => 'JON', 'chapters' => 4],
        ['usfm' => 'MIC', 'chapters' => 7], ['usfm' => 'NAM', 'chapters' => 3],
        ['usfm' => 'HAB', 'chapters' => 3], ['usfm' => 'ZEP', 'chapters' => 3],
        ['usfm' => 'HAG', 'chapters' => 2], ['usfm' => 'ZEC', 'chapters' => 14],
        ['usfm' => 'MAL', 'chapters' => 4], ['usfm' => 'MAT', 'chapters' => 28],
        ['usfm' => 'MRK', 'chapters' => 16], ['usfm' => 'LUK', 'chapters' => 24],
        ['usfm' => 'JHN', 'chapters' => 21], ['usfm' => 'ACT', 'chapters' => 28],
        ['usfm' => 'ROM', 'chapters' => 16], ['usfm' => '1CO', 'chapters' => 16],
        ['usfm' => '2CO', 'chapters' => 13], ['usfm' => 'GAL', 'chapters' => 6],
        ['usfm' => 'EPH', 'chapters' => 6], ['usfm' => 'PHP', 'chapters' => 4],
        ['usfm' => 'COL', 'chapters' => 4], ['usfm' => '1TH', 'chapters' => 5],
        ['usfm' => '2TH', 'chapters' => 3], ['usfm' => '1TI', 'chapters' => 6],
        ['usfm' => '2TI', 'chapters' => 4], ['usfm' => 'TIT', 'chapters' => 3],
        ['usfm' => 'PHM', 'chapters' => 1], ['usfm' => 'HEB', 'chapters' => 13],
        ['usfm' => 'JAS', 'chapters' => 5], ['usfm' => '1PE', 'chapters' => 5],
        ['usfm' => '2PE', 'chapters' => 3], ['usfm' => '1JN', 'chapters' => 5],
        ['usfm' => '2JN', 'chapters' => 1], ['usfm' => '3JN', 'chapters' => 1],
        ['usfm' => 'JUD', 'chapters' => 1], ['usfm' => 'REV', 'chapters' => 22],
    ];

    public function run()
    {
        reading_schedules::truncate();

        $day = 1;
        $bookIndex = 0;
        $chapter = 1;

        while ($day <= 298) {
            $morning = [];
            $evening = [];

            // Pagi: ambil hingga 2 pasal, lanjut ke kitab berikutnya jika habis
            for ($i = 0; $i < 2; $i++) {
                if ($bookIndex >= count($this->books)) {
                    $bookIndex = 0;
                    $chapter = 1;
                }

                if ($chapter > $this->books[$bookIndex]['chapters']) {
                    $bookIndex++;
                    $chapter = 1;
                    if ($bookIndex >= count($this->books)) {
                        $bookIndex = 0;
                    }
                }

                $morning[] = $this->books[$bookIndex]['usfm'] . '.' . $chapter;
                $chapter++;
            }

            // Malam: ambil hingga 2 pasal, lanjut ke kitab berikutnya jika habis
            for ($i = 0; $i < 2; $i++) {
                if ($bookIndex >= count($this->books)) {
                    $bookIndex = 0;
                    $chapter = 1;
                }

                if ($chapter > $this->books[$bookIndex]['chapters']) {
                    $bookIndex++;
                    $chapter = 1;
                    if ($bookIndex >= count($this->books)) {
                        $bookIndex = 0;
                    }
                }

                $evening[] = $this->books[$bookIndex]['usfm'] . '.' . $chapter;
                $chapter++;
            }

            // Selalu create entry (pasti ada isi karena logika di atas)
            reading_schedules::create([
                'day' => $day,
                'morning_passage' => implode('-', $morning),
                'evening_passage' => implode('-', $evening),
            ]);

            $day++;
        }
    }
}
