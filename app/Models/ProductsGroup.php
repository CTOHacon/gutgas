<?php

namespace App\Models;

use App\Traits\MetableExtention;
use App\Traits\Seoable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Zoha\Metable;

class ProductsGroup extends Model
{
    use HasFactory, Seoable, HasTranslations, Metable, MetableExtention;

    protected $guarded = [];

    public $translatable = ['name'];

    public $timestamps = false;

    protected $with = ['seoEntity', 'meta'];

    protected $metaTable = 'productsGroups_meta';

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function seoController()
    {
        return 'App\Http\Controllers\ProductsGroupController';
    }
}
