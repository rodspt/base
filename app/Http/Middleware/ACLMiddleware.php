<?php

namespace App\Http\Middleware;

use App\Services\RouteService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class ACLMiddleware
{
    public function __construct(private RouteService $routeService)
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $routeName = Route::currentRouteName();

        if(!$this->routeService->hasPermissions($request->user(), $routeName))
        {
           abort(Response::HTTP_FORBIDDEN, 'Not Authorized');
        }


        return $next($request);
    }
}
