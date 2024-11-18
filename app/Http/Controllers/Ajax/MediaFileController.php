<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaFileResource;
use App\Models\MediaFile;
use App\Services\MediaFilesService;
use Illuminate\Http\Request;

class MediaFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'lastItemId' => ['nullable', 'integer'],
            'search' => ['nullable', 'string'],
        ]);
        $mediaFiles = MediaFile::query();
        if ($request->lastItemId) $mediaFiles->where('id', '<', $request->lastItemId);
        if ($request->search) $mediaFiles->where('name', 'LIKE', '%' . $request->search . '%');

        return $mediaFiles->orderBy('id', 'desc')->limit(24)->get();
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
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $mediaFile = MediaFilesService::store($request->file('file'));

        try {
            return response()->json($mediaFile, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MediaFile $mediaFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MediaFile $mediaFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MediaFile $mediaFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MediaFile $mediaFile)
    {
        if ($mediaFile) $mediaFile->delete();

        return response()->json([
            'message' => 'Media file deleted successfully',
        ], 204);
    }
}
