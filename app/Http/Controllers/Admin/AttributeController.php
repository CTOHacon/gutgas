<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attribute\StoreRequest;
use App\Http\Requests\Attribute\UpdateRequest;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        Attribute::create($request->validated());

        return redirect()->back()->with('message', ['type' => 'success', 'text' => 'Attribute created successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Attribute $attribute)
    {
        $attribute->update($request->validated());

        return redirect()->back()->with('message', ['type' => 'success', 'text' => 'Attribute updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return redirect()->back()->with('message', ['type' => 'success', 'text' => 'Attribute deleted successfully.']);
    }
}
