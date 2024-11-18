<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Display a listing of the resource for admin
     */
    public function indexAdmin(string $search = '')
    {
        if (request()->routeIs('admin.page.searchAdmin') && empty($search)) {
            return redirect()->route('admin.page.indexAdmin');
        }

        $pages = Page::orderBy('id', 'desc');
        if (!empty($search)) {
            $pages->where('title', 'LIKE', "%{$search}%")
                ->orWhereHas('seoEntity', function ($query) use ($search) {
                    $query->where('slug', 'LIKE', "%{$search}%");
                })
                ->paginate(15);
        }
        return inertia('Admin/Page/Index')->with([
            'pages' => $pages->paginate(15),
            'pageTypes' => array_keys(Page::PAGE_TYPES),
            'search' => $search,
        ]);
    }

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
        return Inertia::render('Admin/Page/Create', [
            'pageTypes' => array_keys(Page::PAGE_TYPES),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(Page::PAGE_TYPES)),
        ]);

        $page = Page::create([
            'type' => $request->type,
        ]);

        return redirect()->route('admin.page.edit', $page->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        if (!array_key_exists($page->type, Page::PAGE_TYPES)) {
            abort(404);
        }

        $controller = Page::PAGE_TYPES[$page->type];

        // get controller required parameters
        $reflection = new \ReflectionMethod($controller, '__construct');
        $parameters = $reflection->getParameters();
        $controllerParameters = [];
        foreach ($parameters as $parameter) {
            if ($parameter->getType()->getName()) {
                $controllerParameters[] = app()->make($parameter->getType()->getName());
            }
        }

        return (app()->make($controller, $controllerParameters)
        )->edit($page);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $page->update(
            $request->validate([
                'title' => 'string|nullable',
                'content' => 'string|nullable',
            ])
        );

        $page->setMetaFromRequest($request);

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Page updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.page.indexAdmin')->with([
            'message' => [
                'type' => 'success',
                'text' => 'Page deleted successfully'
            ]
        ]);
    }
}
