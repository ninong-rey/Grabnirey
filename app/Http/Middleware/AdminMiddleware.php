<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
{
    if (!$request->session()->get('is_admin')) {
        // Store intended URL for redirect after login
        if ($request->isMethod('get')) {
            $request->session()->put('url.intended', $request->url());
        }
        return redirect()->route('admin.login')->with('error', 'Please login as administrator.');
    }

    return $next($request);
}
}
