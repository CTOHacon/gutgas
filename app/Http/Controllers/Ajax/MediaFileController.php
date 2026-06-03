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

        // Only presence/size is validated here. The real type gate is below:
        // the SAVED extension is derived SOLELY from the server-detected MIME
        // type, never from the client-supplied filename. This is what prevents
        // uploading executable PHP (incl. GIF/image polyglots named *.php).
        $request->validate([
            'file' => ['required', 'file', 'max:5120'], // 5 MB
        ]);

        $uploadedFile = $request->file('file');
        $realMime     = $uploadedFile->getMimeType();                          // sniffed from content (finfo)
        $clientExt    = strtolower($uploadedFile->getClientOriginalExtension());

        // Canonical, server-trusted MIME -> extension map. SVG is handled
        // separately because finfo reports it inconsistently (xml/plain/svg).
        $mimeToExt = [
            'image/jpeg'      => 'jpg',
            'image/png'       => 'png',
            'image/gif'       => 'gif',
            'image/webp'      => 'webp',
            'application/pdf' => 'pdf',
        ];

        // Sanitized base name: no path, no special chars, no extension.
        $baseName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBase = trim(preg_replace('/-+/', '-', preg_replace('/[^a-z0-9\-]+/i', '-', mb_strtolower($baseName))), '-');
        $safeBase = $safeBase !== '' ? $safeBase : 'file';

        // --- SVG branch: only when the client claims .svg AND content is a real SVG ---
        if ($clientExt === 'svg') {
            try {
                $svgContent = file_get_contents($uploadedFile->getPathname());
                if ($svgContent === false || stripos($svgContent, '<svg') === false) {
                    return response()->json(['message' => 'Invalid SVG'], 422);
                }

                if (class_exists(\Enshrined\SvgSanitizer\Sanitizer::class)) {
                    $clean = (new \Enshrined\SvgSanitizer\Sanitizer())->sanitize($svgContent);
                    if (! $clean) {
                        return response()->json(['message' => 'Invalid SVG'], 422);
                    }
                    $svgContent = $clean;
                } else {
                    // Fallback manual stripping of active content
                    $svgContent = preg_replace('/<script.*?>.*?<\/script>/is', '', $svgContent);
                    $svgContent = preg_replace('/\son\w+\s*=\s*"[^"]*"/i', '', $svgContent);
                    $svgContent = preg_replace("/\son\w+\s*=\s*'[^']*'/i", '', $svgContent);
                    $svgContent = preg_replace('/<foreignObject.*?>.*?<\/foreignObject>/is', '', $svgContent);
                    $svgContent = preg_replace('/(?:javascript|data)\s*:/i', '', $svgContent);
                }

                // Ensure exactly one <svg> root
                if (substr_count(strtolower($svgContent), '<svg') !== 1) {
                    return response()->json(['message' => 'Malformed SVG'], 422);
                }

                $safeName = $safeBase . '.svg';
                $tmpPath  = tempnam(sys_get_temp_dir(), 'svg');
                file_put_contents($tmpPath, $svgContent);
                $uploadedFile = new \Illuminate\Http\UploadedFile(
                    $tmpPath, $safeName, 'image/svg+xml', null, true // test mode: skip isValid()
                );
            } catch (\Throwable $e) {
                \Log::warning('SVG sanitization failed: ' . $e->getMessage());
                return response()->json(['message' => 'SVG processing failed'], 422);
            }

            return $this->persistMediaFile($uploadedFile, $safeName);
        }

        // --- Raster / PDF branch: extension comes ONLY from the detected MIME ---
        if (! isset($mimeToExt[$realMime])) {
            return response()->json(['message' => 'Invalid or unsupported file type'], 422);
        }
        $safeName = $safeBase . '.' . $mimeToExt[$realMime];

        return $this->persistMediaFile($uploadedFile, $safeName);
    }

    /**
     * Persist an already-validated, safely-named upload.
     */
    private function persistMediaFile(\Illuminate\Http\UploadedFile $file, string $safeName)
    {
        try {
            $mediaFile = MediaFilesService::store($file, $safeName);

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
