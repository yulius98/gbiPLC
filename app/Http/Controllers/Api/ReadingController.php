<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ReadingSchedule;
use App\Models\reading_schedules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ReadingController extends Controller
{
    // Mapping USFM → kode singkat alkita.mobi (wajib tepat!)
    private $bookMap = [
        'GEN' => 'Kej', 'EXO' => 'Kel', 'LEV' => 'Ima', 'NUM' => 'Bil', 'DEU' => 'Ul',
        'JOS' => 'Yos', 'JDG' => 'Hak', 'RUT' => 'Rut',
        '1SA' => '1Sam', '2SA' => '2Sam', '1KI' => '1Raj', '2KI' => '2Raj',
        '1CH' => '1Taw', '2CH' => '2Taw', 'EZR' => 'Ezr', 'NEH' => 'Neh', 'EST' => 'Est',
        'JOB' => 'Ayb', 'PSA' => 'Mzm', 'PRO' => 'Ams', 'ECC' => 'Pkh', 'SNG' => 'Kid',
        'ISA' => 'Yes', 'JER' => 'Yer', 'LAM' => 'Rat', 'EZK' => 'Yeh', 'DAN' => 'Dan',
        'HOS' => 'Hos', 'JOL' => 'Yoe', 'AMO' => 'Amo', 'OBA' => 'Oba', 'JON' => 'Yun',
        'MIC' => 'Mi',  'NAM' => 'Nah', 'HAB' => 'Hab', 'ZEP' => 'Zef', 'HAG' => 'Hag',
        'ZEC' => 'Za',  'MAL' => 'Mal',
        'MAT' => 'Mat', 'MRK' => 'Mrk', 'LUK' => 'Luk', 'JHN' => 'Yoh', 'ACT' => 'Kis',
        'ROM' => 'Rom', '1CO' => '1Kor', '2CO' => '2Kor', 'GAL' => 'Gal', 'EPH' => 'Ef',
        'PHP' => 'Flp', 'COL' => 'Kol', '1TH' => '1Tes', '2TH' => '2Tes',
        '1TI' => '1Tim', '2TI' => '2Tim', 'TIT' => 'Tit', 'PHM' => 'Flm',
        'HEB' => 'Ibr', 'JAS' => 'Yak', '1PE' => '1Pet', '2PE' => '2Pet',
        '1JN' => '1Yoh', '2JN' => '2Yoh', '3JN' => '3Yoh', 'JUD' => 'Yud', 'REV' => 'Why'
    ];

    public function today(Request $request)
    {
        $user = $request->user();

        // Gunakan reading_start_date user, atau default awal tahun
        $startDate = $user->reading_start_date
            ? Carbon::parse($user->reading_start_date)
            : Carbon::now()->startOfYear();

        $daysSinceStart = (int) $startDate->diffInDays(Carbon::now());

        // Cycle through 365 days
        $currentDay = ($daysSinceStart % 365) + 2;

        $schedule = reading_schedules::where('day', $currentDay)->firstOrFail();
        Log::info('Nilai $schedule:', ['schedule' => $schedule]);

        $morning = $this->fetchFromAlkitabMobi($schedule->morning_passage);
        $evening = $this->fetchFromAlkitabMobi($schedule->evening_passage);

        return response()->json([
            'date'       => Carbon::now()->translatedFormat('d F Y'),
            'morning'    => $morning,
            'evening'    => $evening,
            'progress'   => [
                'current_day' => $currentDay,
                'total_days'  => 365
            ]
        ]);
    }

    private function fetchFromAlkitabMobi($passageString)
    {
        // Contoh: "GEN.17-GEN.18" → ['Kej', 17] dan ['Kej', 18]
        $parts = explode('-', $passageString);
        $allVerses = [];

        foreach ($parts as $part) {
            [$bookCode, $chapter] = explode('.', $part);
            $shortCode = $this->bookMap[$bookCode] ?? 'Kej';

            $url = "https://alkitab.mobi/tb/{$shortCode}/{$chapter}/";

            $cacheKey = "alkitab_mobi_{$shortCode}_{$chapter}";
            $html = Cache::remember($cacheKey, now()->addDays(7), function () use ($url) {
                $context = stream_context_create(['http' => ['timeout' => 10]]);
                $content = @file_get_contents($url, false, $context);
                return $content ?: '<body></body>';
            });

            // Parsing ayat dari HTML alkitab.mobi
            // Format: <span class="reftext"><a name=v1 ...>1</a></span> <span data-dur="...">Teks ayat</span>
            preg_match_all(
                '/<span class="reftext"><a name=v(\\d+)[^>]*>(\\d+)<\\/a><\\/span>\\s*<span[^>]*>([^<]+)<\\/span>/s',
                $html,
                $matches,
                PREG_SET_ORDER
            );

            foreach ($matches as $m) {
                $allVerses[] = [
                    'verse'    => (int)$m[1],
                    'text'     => trim($m[3]),
                    'audioUrl' => "https://audio.alkitab.mobi/tb/{$shortCode}/{$chapter}/{$m[1]}.mp3"
                ];
            }
        }

        // Referensi cantik
        $prettyRef = strtr($passageString, ['GEN' => 'Kejadian', 'EXO' => 'Keluaran', 'LEV' => 'Imamat', 'MAT' => 'Matius', 'MRK' => 'Markus', 'LUK' => 'Lukas', 'JHN' => 'Yohanes', 'ACT' => 'Kisah Para Rasul', 'REV' => 'Wahyu']);

        // Extract first book code for audio
        $firstPart = $parts[0] ?? 'GEN.1';
        $firstBookCode = explode('.', $firstPart)[0];
        $firstChapter = explode('.', $firstPart)[1] ?? '1';
        $firstBookShort = $this->bookMap[$firstBookCode] ?? 'Kej';

        return [
            'data' => [
                'reference'   => $prettyRef . ' (TB)',
                'content'     => $allVerses,
                'audio'       => count($allVerses) > 0 ? "https://audio.alkitab.mobi/tb/{$firstBookShort}/{$firstChapter}.mp3" : null
            ]
        ];
    }

    public function setStartDate(Request $request)
    {
        $user = $request->user();
        if ( is_null($user->reading_start_date)){
            $request->validate(['start_date' => 'required|date']);
            $request->user()->update(['reading_start_date' => $request->start_date]);
            return response()->json([
                'status' => true,
                'tanggal_mulai' => $request->start_date,
                'message' => 'Mulai membaca Alkitab' ]);
        } else {
            return response()->json([
                'status' => false,
                'tanggal_mulai' => $user->reading_start_date,
                'message' => 'Lanjut membaca Alkitab' ]);
        }
    }
}
