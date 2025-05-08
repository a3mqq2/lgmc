<?php
// app/Http/Middleware/EnsureDocumentsCompleted.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EnsureDocumentsCompleted
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $doctor = Auth::guard('doctor')->user();

        // إذا لم يُسجَّل دخول أو لم يكمل رفع المستندات
        if (! $doctor || ! $doctor->documents_completed) {
            return redirect()->route('upload-documents')
                             ->withErrors('يجب رفع جميع المستندات المطلوبة أولاً.');
        }

        return $next($request);
    }
}
