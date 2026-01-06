<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TblEvent;
use App\Models\TblCarousel;
use App\Models\TblPopupAds;
use Illuminate\Http\Request;
use App\Models\TblPastorNote;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PageJemaatController extends Controller
{

    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display the main jemaat page
     */
    public function index()
    {
        $popupAds = TblPopupAds::all();

        $carousels = TblCarousel::all();

        $latestPastorNote = TblPastorNote::orderBy('tgl_note', 'desc')
            ->first();

        $latestEvent = TblEvent::orderBy('tgl_event', 'desc')->first();

        if ($latestEvent) {
            $latestMonth = Carbon::parse($latestEvent->tgl_event)->month;
            $latestYear = Carbon::parse($latestEvent->tgl_event)->year;

            $events = TblEvent::whereMonth('tgl_event', $latestMonth)
                ->whereYear('tgl_event', $latestYear)
                ->orderBy('tgl_event', 'asc')
                ->paginate(10);
        } else {
            $events = collect(); // Tidak ada event sama sekali
        }

        $birthdayMembers = User::whereMonth('tgl_lahir', Carbon::now()->month)
            ->orderby('tgl_lahir','asc')
            ->paginate(8);

        return view('page-jemaat', compact('popupAds', 'carousels', 'latestPastorNote', 'birthdayMembers','events'));
    }

    /**
     * Show the profile page for the authenticated user
     */
    public function myProfile()
    {
        $user = Auth::user();
        return view('myprofile', compact('user'));
    }

    /**
     * Show the profile edit form
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('jemaat.editprofile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tgl_lahir' => 'nullable|date|before:today',
            'alamat' => 'nullable|string|max:500',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_HP' => 'nullable|string|max:20',
            'gol_darah' => 'nullable|string|max:3|in:A,B,AB,O',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'filename' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Update user fields
        $user->fill($validated);

        // Handle photo upload
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
            $user->filename = $this->fileUploadService->replaceFile(
                $file,
                'foto-jemaat',
                $validated['name'],
                $user->filename
            );
        }

        $user->save();

        return redirect()->route('page-jemaat')->with('success', 'Profil berhasil diperbarui!');
    }
}
