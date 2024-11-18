<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductFilter extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];

    public $timestamps = false;

    public $translatable = ['name'];

    public $with = ['productFilterValues'];

    public function productFilterValues()
    {
        return $this->hasMany(ProductFilterValue::class)->orderBy('sequence');
    }
}
