<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductFilterValue extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];

    public $timestamps = false;

    public $translatable = ['value'];

    public function productFilter()
    {
        return $this->belongsTo(ProductFilter::class);
    }
}
