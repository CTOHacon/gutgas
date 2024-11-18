<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoRedirectionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->getRequestUri();

        if (
            preg_match('/^\/index.php/', $path)
            ||
            preg_match('/\/$/', $path)
            &&
            $path !== '/'
        ) {
            $path = preg_replace('/^\/index.php/', '', $path);
            $path = preg_replace('/\/$/', '', $path);

            $url = $request->getSchemeAndHttpHost() . $path;

            return redirect()->to(
                $url,
                301
            );
        }

        return $next($request);
    }
}
