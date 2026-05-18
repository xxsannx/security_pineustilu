<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to add cache control headers for static assets.
 * Improves performance by allowing browsers to cache responses.
 */
class CacheControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $maxAge = '86400'): Response
    {
        $response = $next($request);

        // Only cache GET requests
        if ($request->isMethod('GET')) {
            // Don't cache authenticated user specific content
            if (!$request->user()) {
                $response->headers->set('Cache-Control', "public, max-age={$maxAge}");
            } else {
                $response->headers->set('Cache-Control', 'private, max-age=0, must-revalidate');
            }
        }

        return $response;
    }
}
