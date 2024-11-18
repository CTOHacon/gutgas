<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeGroup\UpdateRequest;
use App\Models\AttributeGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttributeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/AttributeGroup/Index', [
            'attributeGroups' => AttributeGroup::withCount('attributes')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/AttributeGroup/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateRequest $request)
    {
        $attributeGroup = AttributeGroup::create($request->validated());

        return redirect()->route('admin.attributeGroup.edit', $attributeGroup->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(AttributeGroup $attributeGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttributeGroup $attributeGroup)
    {
        return Inertia::render('Admin/AttributeGroup/Edit', [
            'attributeGroup' => $attributeGroup->load('attributes'),
            'attributes' => $attributeGroup->attributes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, AttributeGroup $attributeGroup)
    {
        $attributeGroup = $attributeGroup->update($request->validated());

        return redirect()->back()->with('message', ['type' => 'success', 'text' => 'Attribute Group updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttributeGroup $attributeGroup)
    {
        $attributeGroup->delete();

        return redirect()->route('admin.attributeGroup.index');
    }
}
