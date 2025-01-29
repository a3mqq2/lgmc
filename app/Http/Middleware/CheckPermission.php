<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {


        if (auth()->user()->hasPermissionTo($permission)) {
            return $next($request);
        } else {
            abort(403, 'ليس لديك الصلاحية للوصول إلى هذه الصفحة');
        }

    }
}
