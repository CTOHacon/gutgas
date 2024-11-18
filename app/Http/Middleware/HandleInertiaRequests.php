<?php

namespace App\Http\Middleware;

use App\Models\GlobalSettings;
use App\Models\SeoEntity;
use App\Services\PageNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware {
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array {
        $user = Auth::user();

        if($request->routeIs('logout'))
            $user = null;
        return array_merge(parent::share($request), [
            // message
            'message' => $request->session()->get('message'),

            'baseUrl' => url('/'),
            'currentUrl' => $request->fullUrl(),

            'auth' => [
                'user' => $user,
                'permissions' => $user ? $user->getAllPermissions()->pluck('name') : [],
            ],

            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },

            'globalSettings' => function () {
                return GlobalSettings::all();
            },

            'pageNumber' => PageNumberService::getPageNumber(),
        ]);
    }
}
