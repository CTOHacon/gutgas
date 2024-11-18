<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageWithSidebarSliderController extends Controller
{
    public function __construct()
    {
    }

    public function show(Request $request, Page $page)
    {
        return Inertia::render('PageWithSidebarSlider', [
            'page' => $page,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return Inertia::render('Admin/Page/PageWithSidebarSliderEdit', [
            'page' => $page,
        ]);
    }
}
