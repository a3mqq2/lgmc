<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {

        if($role == "finance") {
            if(auth()->user()->roles->where('name','finance-general') || auth()->user()->roles->where('name','finance-branch')) {
                abort(403, 'Unauthorized action.');
            }
        }

        if (auth()->user()->roles->where('name', $role)->count() == 0) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
