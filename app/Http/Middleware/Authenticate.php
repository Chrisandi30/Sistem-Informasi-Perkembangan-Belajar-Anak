<?php

namespace App\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function authenticate($request, array $guards): void
    {
        $guards = empty($guards) ? [null] : $guards;
        $preferredGuard = (string) $request->session()->get('active_guard', '');

        if ($preferredGuard !== '' && in_array($preferredGuard, $guards, true)) {
            $guards = array_values(array_unique([$preferredGuard, ...$guards]));
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);

                if ($guard !== null) {
                    $request->session()->put('active_guard', $guard);
                }

                return;
            }
        }

        $this->unauthenticated($request, $guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->redirectTo($request),
        );
    }
}
