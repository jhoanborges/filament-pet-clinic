<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class Subscribed
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $user = Auth::user();

        if (! $user || ! $user->subscribed()) {
            // Redirect user to billing page and ask them to subscribe...
            return redirect('/subscription-checkout');
        }

        return $next($request);
    }
}
