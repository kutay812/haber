<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class loginCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            if (auth()->user()->hasAnyRole(['Super Admin', 'Admin', 'Editor'])) {
                return redirect()->route('admin.index');
            }
            return redirect()->route('welcome');
        }

        return $next($request);
    }
}