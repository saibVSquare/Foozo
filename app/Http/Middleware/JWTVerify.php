<?php

namespace App\Http\Middleware;

use App\Http\Libraries\ResponseBuilder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTVerify extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->action['as'];
        $notAuthorizedRouteNames = [
            'api.login',
            'api.signup'
        ];

        if (!$token = $this->auth->setRequest($request)->getToken()) {
            if (!in_array($routeName, $notAuthorizedRouteNames)) {
                return (new ResponseBuilder(401, __('Token not provided')))->build();
            } else {
                return $next($request);
            }
        }
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                logger('Invalid Err =>', [$e]);
                return responseBuilder()->error(__('Token is Invalid '));
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return responseBuilder()->error(__('Token is Expired'));
            } else {
                return responseBuilder()->error(__('Authorization Token not found'));
            }
        }
        return $next($request);
    }
}
