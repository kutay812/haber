<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->hasRole('User')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Erişim yetkiniz yok.'], 403);
            }
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Bu alana erişim yetkiniz bulunmamaktadır.');
        }

        return $next($request);
    }
} 