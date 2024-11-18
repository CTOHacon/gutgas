<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class RequiredProductsGroup extends Model
{
    use HasFactory, HasJsonRelationships, HasTranslations {
        HasTranslations::getAttributeValue insteadof HasJsonRelationships;
    }

    protected $casts = [
        'product_ids' => 'json',
    ];

    public $timestamps = false;

    protected $with = ['products'];

    public $translatable = ['name'];

    protected $guarded = [];

    public function products()
    {
        return $this->belongsToJson(Product::class, 'product_ids', 'id');
    }
}
