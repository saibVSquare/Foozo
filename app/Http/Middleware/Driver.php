<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Driver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user->isDriver()) {
            return $next($request);
        } else {
            if ($request->expectsJson()) {
                return responseBuilder()->error(__('You are not allow to access this page'));
            } else {
                return redirect(route('dashboard'))->with('error', __('You are not allow to access this page'));
            }
        }    }
}
