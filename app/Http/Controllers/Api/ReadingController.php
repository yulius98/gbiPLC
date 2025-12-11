<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\reading_schedules;
use App\Models\ReadingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ReadingController extends Controller
{
    public function today(Request $request)
    {
        $user = $request->user();
        $startDate = $user->reading_start_date ? Carbon::parse($user->reading_start_date) : Carbon::today();

        $daysSinceStart = $startDate->diffInDays(Carbon::now()->startOfDay());


        $schedule = reading_schedules::where('day', $daysSinceStart + 1)->firstOrFail();
        Log::info('Nilai $schedule:', ['schedule' => $schedule]);

        $morning = $this->fetchPassages($schedule->morning_passage);
        $evening = $this->fetchPassages($schedule->evening_passage);


        return response()->json([
            'date' => Carbon::now()->format('d M Y'),
            'morning' => $morning,
            'evening' => $evening,
            'progress' => [
                'current_day' => $daysSinceStart + 1,
                'total_days' => 365
            ]
        ]);
    }

    public function fetchPassages($passage)
    {
        // Ganti bibleId ke ID TB Indonesia (konfirmasi via /v1/bibles?language=ind)
        $bibleId = '2dd568eeff29fb3c-02';  // Plain Indonesian Translation (ganti jika dapat ID TB asli)
        $results = [];

        // Cek apakah $passage adalah range
        if (preg_match('/^([A-Z]+)\.(\d+)-([A-Z]+)\.(\d+)$/', $passage, $matches)) {
            $bookStart = $matches[1];
            $chapterStart = (int)$matches[2];
            $bookEnd = $matches[3];
            $chapterEnd = (int)$matches[4];

            // Pastikan bookStart == bookEnd (Scripture API tidak support lintas kitab dalam satu request)
            if ($bookStart === $bookEnd) {
                for ($i = $chapterStart; $i <= $chapterEnd; $i++) {
                    $singlePassage = "{$bookStart}.{$i}";
                    $results[] = $this->fetchSinglePassage($bibleId, $singlePassage);
                }
            }
        } else {
            $results[] = $this->fetchSinglePassage($bibleId, $passage);
        }

        return $results;
    }

    private function fetchSinglePassage($bibleId, $passage)
    {
        $cacheKey = 'bible_passage_' . md5($bibleId . '_' . $passage);
        return cache()->remember($cacheKey, now()->addHours(24), function () use ($bibleId, $passage) {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://rest.api.bible/v1/bibles/{$bibleId}/passages/{$passage}", [
                'headers' => [
                    'api-key' => env('BIBLE_API_KEY'),
                    'Accept' => 'application/json'
                ],
                'query' => [
                    'content-type' => 'text',
                    'include-notes' => 'false',
                    'include-titles' => 'true',
                    'include-chapter-numbers' => 'true',
                    'include-verse-numbers' => 'true',
                    'include-verse-spans' => 'false'
                ]
            ]);
            return json_decode($response->getBody(), true);
        });
    }

    public function fetchAudio($chapterId)  // e.g., 'GEN.1'
    {
        $bibleId = '2dd568eeff29fb3c-02';  // Sama seperti atas (ada versi dengan audio?)
        $cacheKey = 'bible_audio_' . md5($bibleId . '_' . $chapterId);
        return cache()->remember($cacheKey, now()->addHours(24), function () use ($bibleId, $chapterId) {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://api.scripture.api.bible/v1/bibles/{$bibleId}/audio/chapters/{$chapterId}", [
                'headers' => [
                    'api-key' => env('BIBLE_API_KEY'),
                    'Accept' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['data']['downloadUrls'][0]['url'] ?? null;  // Ambil URL audio MP3
        });
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
