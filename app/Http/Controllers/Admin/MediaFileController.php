<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaFileResource;
use App\Models\MediaFile;
use Illuminate\Http\Request;

class MediaFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Admin/MediaFile/Index');
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
        // foreach ($request->files as $file) {
        //     dd($file->getSize(), $file->getMimeType(), $file->getOriginalName(), $file->getClientOriginalExtension());
        // }
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
        //
    }
}
