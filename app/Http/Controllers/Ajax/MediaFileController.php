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
        // Authorization
        $this->authorize('create', MediaFile::class);

        // Validation (add svg)
        $validated = $request->validate([
            'file' => [
            'required',
            'file',
            'max:5120', // 5 MB
            'mimes:jpg,jpeg,png,gif,webp,pdf,svg',
            'mimetypes:image/jpeg,image/png,image/gif,image/webp,application/pdf,image/svg+xml,text/plain,text/xml,application/xml',
            ],
        ]);

        $uploadedFile = $validated['file'];

        // Real mime (note svg can be reported as text/plain or image/svg+xml)
        $realMime = $uploadedFile->getMimeType();
        $allowedMimes = [
            'image/jpeg','image/png','image/gif','image/webp',
            'application/pdf','image/svg+xml','text/plain','text/xml','application/xml'
        ];
        if (! in_array($realMime, $allowedMimes)) {
            return response()->json(['message' => 'Invalid file type'], 422);
        }

        // Sanitize original filename
        $baseName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBase = preg_replace('/[^a-z0-9\-]+/i', '-', mb_strtolower($baseName));
        $safeBase = trim(preg_replace('/-+/', '-', $safeBase), '-');
        $extension = strtolower($uploadedFile->getClientOriginalExtension());
        $safeName = ($safeBase ?: 'file') . '.' . $extension;

        // SVG specific sanitization
        if ($extension === 'svg') {
            try {
            $svgContent = file_get_contents($uploadedFile->getPathname());

            // Basic quick rejects
            if (stripos($svgContent, '<script') !== false ||
                preg_match('/on\w+=/i', $svgContent) ||
                preg_match('/javascript:/i', $svgContent) ||
                preg_match('/data:[^;]+;base64/i', $svgContent)
            ) {
                // Optional: attempt cleanup instead of reject
            }

            // If library enshrined/svg-sanitizer is available, use it
            if (class_exists(\Enshrined\SvgSanitizer\Sanitizer::class)) {
                $sanitizer = new \Enshrined\SvgSanitizer\Sanitizer();
                $dirty = $svgContent;
                $clean = $sanitizer->sanitize($dirty);
                if (! $clean) {
                return response()->json(['message' => 'Invalid SVG'], 422);
                }
                $svgContent = $clean;
            } else {
                // Minimal manual stripping: remove scripts / event handlers / foreignObject
                $svgContent = preg_replace('/<script.*?>.*?<\/script>/is', '', $svgContent);
                $svgContent = preg_replace('/on\w+="[^"]*"/i', '', $svgContent);
                $svgContent = preg_replace('/on\w+=\'[^\']*\'/i', '', $svgContent);
                $svgContent = preg_replace('/<foreignObject.*?>.*?<\/foreignObject>/is', '', $svgContent);
            }

            // Ensure only one <svg ...> root
            if (substr_count(str_ireplace('</svg', '</svg', $svgContent), '<svg') !== 1) {
                return response()->json(['message' => 'Malformed SVG'], 422);
            }

            // Write sanitized content to a temp file and replace UploadedFile
            $tmpPath = tempnam(sys_get_temp_dir(), 'svg');
            file_put_contents($tmpPath, $svgContent);
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $tmpPath,
                $safeName,
                'image/svg+xml',
                null,
                true // mark as test (skip isValid() upload checks)
            );
            } catch (\Throwable $e) {
            \Log::warning('SVG sanitization failed: ' . $e->getMessage());
            return response()->json(['message' => 'SVG processing failed'], 422);
            }
        }

        try {
            $mediaFile = MediaFilesService::store($uploadedFile, $safeName);

            return response()->json([
            'message' => 'Uploaded successfully',
            'data' => $mediaFile,
            ], 201);
        } catch (\Throwable $e) {
            \Log::error('Media upload failure: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Upload failed'], 500);
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
