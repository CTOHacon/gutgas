<?php

namespace App\Services;

use App\Models\MediaFile;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class MediaFilesService
{
    public function __construct()
    {
        //
    }

    static function getUniqFileName(string $baseFilename)
    {
        $filename = pathinfo($baseFilename, PATHINFO_FILENAME);
        $extension = pathinfo($baseFilename, PATHINFO_EXTENSION);

        if (file_exists(storage_path('app/public/media/' . $baseFilename))) {
            $i = 1;
            while (file_exists(storage_path('app/public/media/' . $filename . $i . '.' . $extension))) {
                $i++;
            }
            $filename = $filename . $i;
        }

        return $filename . '.' . $extension;
    }

    static function store(\Illuminate\Http\UploadedFile $file)
    {
        $fileBaseName = self::getUniqFileName($file->getClientOriginalName());
        $fileName = pathinfo($fileBaseName, PATHINFO_FILENAME);
        $extension = pathinfo($fileBaseName, PATHINFO_EXTENSION);
        $path = $file->storeAs('public/media', $fileBaseName);

        DB::beginTransaction();

        try {
            $mediaFile = MediaFile::create([
                'name' => $fileName,
                'extension' => $extension,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'path' => 'app/' . $path,
                'url' => '/storage/media/' . $fileBaseName,
            ]);
            DB::commit();

            return $mediaFile;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    static function cleanUp()
    {
        $mediaFiles = MediaFile::all()->each(function ($mediaFile) {
            echo 'Deleting MediaFile: ' . $mediaFile->id . "\n";
            $mediaFile->delete();
        });
    }
}
