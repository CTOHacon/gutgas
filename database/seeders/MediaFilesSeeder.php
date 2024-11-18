<?php

namespace Database\Seeders;

use App\Models\MediaFile;
use App\Models\ScaledMediaFile;
use App\Services\MediaFilesService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MediaFilesService::cleanUp();

        $files = Storage::files('public/media');
        foreach ($files as $file) {
            Storage::delete($file);
        }

        $files = Storage::files('example-images');
        foreach ($files as $file) {
            $uploadedFile = new UploadedFile(
                storage_path('app/' . $file),
                $file,
                mime_content_type(storage_path('app/' . $file)),
                null,
                true
            );

            MediaFilesService::store($uploadedFile);
        }

        $mediaFiles = MediaFile::all();
        foreach ($mediaFiles as $mediaFile) {
            $mediaFile->url = str_replace('localhost/', 'localhost:8000/', $mediaFile->url);
            $mediaFile->save();
        }
        $scaledMediaFiles = ScaledMediaFile::all();
        foreach ($scaledMediaFiles as $scaledMediaFile) {
            $scaledMediaFile->url = str_replace('localhost/', 'localhost:8000/', $scaledMediaFile->url);
            $scaledMediaFile->save();
        }
    }
}
