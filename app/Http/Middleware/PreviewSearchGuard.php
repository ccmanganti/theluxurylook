<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreviewSearchGuard
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if (!config('site.launch_ready')) {
            // Never allow a development deployment to be indexed or cached by search.
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');
        }
        return $response;
    }
}
