<?php
namespace App\Http\Middleware;

use App\Models\LegacyUrl;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class LegacyUrlDecision
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isMethod('GET') || $request->is('admin*','up','sitemap.xml','robots.txt')) return $next($request);
        $path='/'.ltrim($request->path(),'/');
        $legacy=LegacyUrl::where('path',$path)->first();
        if ($legacy && $legacy->status === 'redirect' && $legacy->redirect_to) {
            $target = $legacy->redirect_to;
            abort_unless(Str::startsWith($target,'/') && !Str::startsWith($target,'//'), 422);
            return redirect($target, 301);
        }
        if ($legacy && $legacy->status === 'retired') abort(410);
        if ($legacy && $legacy->status === 'needs_content' && config('site.launch_ready')) {
            return response('Content migration is not complete for this legacy URL.', 503)
                ->header('Retry-After', '86400')
                ->header('X-Robots-Tag', 'noindex, nofollow');
        }
        return $next($request);
    }
}
