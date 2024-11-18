<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    static $storagePath = 'app/public/media';

    public const IMAGE_SCALES = [
        'thumbnail' => [
            'width' => 300,
            'height' => 300,
            'crop' => true,
        ],
        'medium' => [
            'width' => 600,
            'height' => 600,
            'crop' => true,
        ],
        'large' => [
            'width' => 1200,
            'height' => 1200,
            'crop' => true,
        ],
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($mediaFile) {
            $filePath = storage_path($mediaFile->path);
            if (file_exists($filePath)) unlink($filePath);
            $mediaFile->deleteScaledItems();
        });

        self::created(function ($mediaFile) {
            $mediaFile->generateScaledItems();
        });
    }

    public function thumbnail()
    {
        return $this->hasOne(ScaledMediaFile::class)->where('scale', 'thumbnail');
    }

    public function scaledItems()
    {
        return $this->hasMany(ScaledMediaFile::class);
    }

    public function scaled(string $scale)
    {
        return $this->scaledItems()->where('scale', $scale)->first();
    }

    public function generateScaledItems()
    {
        if (!in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/bmp', 'image/webp'])) return;

        foreach (self::IMAGE_SCALES as $scale => $sizeData) {
            try {
                $this->scaledItems()->create([
                    'scale' => $scale,
                ]);
            } catch (Exception $e) {
                dd($e);
            }
        }
    }

    public function deleteScaledItems()
    {
        foreach ($this->scaledItems()->get() as $scaledItem) {
            $scaledItem->delete();
        }
    }
}
