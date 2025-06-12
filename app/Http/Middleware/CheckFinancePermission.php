<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFinancePermission
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->hasAnyPermission(['finance-branch', 'finance-general'])) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
