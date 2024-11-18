<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GlobalSettingsController extends Controller
{
    // index
    public function index()
    {
        $settings = GlobalSettings::all();
        return Inertia::render('Admin/GlobalSettingsEdit', [
            'globalSettings' => $settings,
        ]);
    }

    public function updateAll(Request $request)
    {
        $locale = app()->getLocale();
        $settings = $request->input('globalSettings');

        foreach ($settings as $key => $value) {
            if (in_array($key, $request->get('translatableGlobalSettings'))) {
                GlobalSettings::set($key . '_' . $locale, $value);
                continue;
            }
            GlobalSettings::set($key, $value);
        }

        return redirect()->back()->with('message', [
            'type' => 'success',
            'text' => 'Global settings updated successfully',
        ]);
    }
}
