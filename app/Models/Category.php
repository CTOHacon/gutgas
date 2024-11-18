<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model
{
    use HasFactory, HasRecursiveRelationships;

    protected $guarded = [];

    /**
     * Get the products associated with the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the parent category of the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the children categories of the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the descendant categories of the category.
     *
     * @return \Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\Descendants
     */
    public function descendants()
    {
        return $this->descendantsAndSelf();
    }

    /**
     * Get the descendant products for the category. (products of the category and its children categories)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function descedantProducts()
    {
        $categories = $this->descendantsAndSelf();
        return $this->hasManyThrough(Product::class, Category::class, 'parent_id', 'category_id', 'id', 'id')
            ->whereIn('category_id', $categories->pluck('id'));
    }
}
