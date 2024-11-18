<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Category/Index', [
            'categories' => Category::tree()->withCount('products')->get()->toTree(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Category/Create', [
            'categories' => Category::tree()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $category = Category::create($request->validated());

        return redirect()->route('admin.category.index')->with('message', [
            'type' => 'success',
            'text' => 'Category "' . $category->name . '" created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return Inertia::render('Admin/Category/Edit', [
            'category' => $category,
            'categories' => Category::tree()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Category updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // category children
        $childrens = $category->children();
        // category parent
        $parent = $category->parent()->get()->first();
        $parentCategoryId = $parent ? $parent->id : null;
        // category products
        $products = $category->products();

        // set category children parent to category parent
        $childrens->update(['parent_id' => $parentCategoryId]);
        // set category products category to category parent
        $products->update(['category_id' => $parentCategoryId]);

        // delete category
        $category->delete();

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Category deleted successfully',
        ]);;
    }
}
