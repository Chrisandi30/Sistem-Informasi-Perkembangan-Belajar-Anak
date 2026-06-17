<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? ['admin', 'guru', 'kepala_sekolah', 'orang_tua', 'web'] : $guards;
        $preferredGuard = (string) $request->session()->get('active_guard', '');

        if ($preferredGuard !== '' && in_array($preferredGuard, $guards, true)) {
            $guards = array_values(array_unique([$preferredGuard, ...$guards]));
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($guard !== null) {
                    $request->session()->put('active_guard', $guard);
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}

