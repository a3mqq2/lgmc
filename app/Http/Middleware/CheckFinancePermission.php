<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFinancePermission
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->hasAnyPermission(['financial-branch', 'financial-administration'])) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
