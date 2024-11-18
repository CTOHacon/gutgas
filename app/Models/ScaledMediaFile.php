<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class ScaledMediaFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    // boot
    public static function boot()
    {
        parent::boot();

        self::deleting(function ($scaledMediaFile) {
            $filePath = storage_path($scaledMediaFile->path);
            if (file_exists($filePath)) unlink($filePath);
        });

        self::created(function ($scaledMediaFile) {
            $scaledMediaFile->generateScaledMediaFile();
        });
    }

    public function mediaFile()
    {
        return $this->belongsTo(MediaFile::class);
    }

    public function generateScaledMediaFile()
    {
        $mediaFile = $this->mediaFile;
        if (!in_array($mediaFile->mime_type, ['image/jpeg', 'image/png', 'image/bmp', 'image/webp'])) return;

        $scale = $this->scale;
        $scaleConfig = MediaFile::IMAGE_SCALES[$scale];

        $image = Image::make(storage_path($mediaFile->path));

        if ($scaleConfig['crop']) {
            $image->fit($scaleConfig['width'], $scaleConfig['height']);
        } else {
            $image->resize($scaleConfig['width'], $scaleConfig['height'], function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $fileName = $mediaFile->name . '-' . $scale . '.' . $mediaFile->extension;
        $image->save(storage_path('app/public/media/' . $fileName));
        $this->update([
            'path' => 'app/public/media/' . $fileName,
            'url' => '/storage/media/' . $fileName,
            'size' => $image->filesize(),
        ]);
        $this->save();
    }
}
