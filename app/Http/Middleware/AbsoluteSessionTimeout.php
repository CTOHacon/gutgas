<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Enforces an absolute session lifetime regardless of user activity.
 * If the session age exceeds the configured max (default 1440 minutes = 1 day), user is logged out.
 */
class AbsoluteSessionTimeout
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $maxMinutes = (int) config('session.absolute_lifetime', 60 * 24); // fallback 1 day
            $startedAt = $request->session()->get('absolute_started_at');

            if (!$startedAt) {
                $request->session()->put('absolute_started_at', now()->timestamp);
                Log::channel('auth')->info('Absolute session timer initialized', [
                    'user_id' => Auth::id(),
                    'ip' => $request->ip(),
                    'max_minutes' => $maxMinutes,
                ]);
            } else {
                $ageMinutes = (now()->timestamp - (int) $startedAt) / 60;
                if ($ageMinutes >= $maxMinutes) {
                    Log::channel('auth')->info('Absolute session timeout reached', [
                        'user_id' => Auth::id(),
                        'ip' => $request->ip(),
                        'age_minutes' => $ageMinutes,
                        'max_minutes' => $maxMinutes,
                    ]);
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('login')->with('status', __('Your session has expired. Please login again.'));
                }
            }
        }

        return $next($request);
    }
}
