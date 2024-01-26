<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class EmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($request->expectsJson()) {
            if (!$user->isVerified()) {
                return responseBuilder()->error(__('Your email is not verified, please verify your email address'));
            }
            if (!$user->isActive()) {
                return responseBuilder()->error(__('Your account has been suspended. Please contact the admin.'));
            }
        } else {
            $message = __('Your account has been suspended. Please contact the admin.');
            if (!$user->isActive() || !$user->isVerified()) {
                auth()->logout();
                // session()->forget('USER_DATA');
                throw ValidationException::withMessages([
                    'email' => [$message],
                ]);
            }
        }

        return $next($request);
    }
}
