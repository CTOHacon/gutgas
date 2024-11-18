<?php

namespace App\Models;

use App\Traits\MetableExtention;
use App\Traits\Seoable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Zoha\Metable;

class ProductPage extends Model
{
    use HasFactory, HasJsonRelationships, HasTranslations, Metable, MetableExtention, Seoable {
        HasTranslations::getAttributeValue insteadof HasJsonRelationships;
    }

    protected $metaTable = 'productPages_meta';

    public $timestamps = false;

    protected $guarded = [];

    public $translatable = ['title', 'description'];

    protected $casts = [
        'media_gallery' => 'json',
        'characteristic_icons' => 'json',
        'documentations' => 'json',
    ];

    protected $with = [
        'mediaGallery', 'characteristicIcons', 'documentations', 'meta',
        'seoEntity'
    ];

    public function seoController()
    {
        return 'App\Http\Controllers\ProductPageController';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function mediaGallery()
    {
        return $this->belongsToJson(MediaFile::class, 'media_gallery', 'id');
    }

    public function characteristicIcons()
    {
        return $this->belongsToJson(MediaFile::class, 'characteristic_icons', 'id');
    }

    public function documentations()
    {
        return $this->belongsToJson(MediaFile::class, 'documentations', 'id');
    }
}
