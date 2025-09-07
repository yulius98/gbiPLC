<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TblCarousel;
use App\Models\TblPopupAds;
use Illuminate\Http\Request;
use App\Models\TblPastorNote;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PageJemaatController extends Controller
{

    public function index()
    {
        $dtpopup = TblPopupAds::all();

        $dtcarousel = TblCarousel::all();

        $dtpasstornote = TblPastorNote::orderBy('tgl_note', 'desc')
            ->first();

        $dtjemaatultah = User::whereMonth('tgl_lahir', Carbon::now()->month)
            ->orderby('tgl_lahir','asc')
            ->paginate(8);

        return view('page-jemaat',compact('dtpopup','dtcarousel','dtpasstornote','dtjemaatultah'));
    }

    /**
     * Show the profile page for the authenticated user.
     */
    public function myProfile()
    {
    $user = Auth::user();
        return view('myprofile', compact('user'));
    }


    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        return view('jemaat.editprofile',['dtuser'=>$user]);
    }

    public function saveProfile(Request $request)
    {

        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'alamat' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'no_HP' => 'nullable|string|max:30',
            'gol_darah' => 'nullable|string|max:3',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'filename' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // max 5MB
        ]);

        // Update user fields
        $user->name = $validated['name'];
        $user->tgl_lahir = $validated['tgl_lahir'] ?? null;
        $user->alamat = $validated['alamat'] ?? null;
        $user->email = $validated['email'];
        $user->no_HP = $validated['no_HP'] ?? null;
        $user->gol_darah = $validated['gol_darah'] ?? null;
        $user->facebook = $validated['facebook'] ?? null;
        $user->instagram = $validated['instagram'] ?? null;

        // Handle photo upload
        if ($request->hasFile('filename')) {
            $file = $request->file('filename');
            $name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->name);
            $extension = $file->getClientOriginalExtension();
            $filename = $name . '.' . $extension;
            $user->filename = $file->storeAs('foto-jemaat', $filename, 'public');
        } else {
                $user->filename = null;
        }

        $user->save();

        return redirect()->route('page-jemaat')->with('success', 'Profile updated!');
    }


}
