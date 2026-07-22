<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MediaAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('media_registration_id')) {
            return redirect('/media-login');
        }

        return $next($request);
    }
}
