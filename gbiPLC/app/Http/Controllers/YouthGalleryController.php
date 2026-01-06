<?php

namespace App\Http\Controllers;

use App\Models\TblYouthGallery;
use Illuminate\Http\Request;

class YouthGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = TblYouthGallery::ordered()->paginate(12);
        return view('youth.gallery', compact('galleries'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'file' => 'required|file|max:51200', // Max 50MB
            'thumbnail' => 'nullable|image|max:2048',
            'category' => 'nullable|string',
            'event_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'order' => 'integer'
        ]);

        // Upload file
        $filePath = $request->file('file')->store('gallery', 'public');
        $validated['file_path'] = $filePath;

        // Upload thumbnail jika ada
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('gallery/thumbnails', 'public');
            $validated['thumbnail_path'] = $thumbnailPath;
        }

        TblYouthGallery::create($validated);
        return redirect()->back()->with('success', 'Media berhasil ditambahkan!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
