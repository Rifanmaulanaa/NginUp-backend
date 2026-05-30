<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden: Anda tidak memiliki akses ke resource ini',
                ], 403);
            }

            return redirect('/home')->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }

        return $next($request);
    }
}
