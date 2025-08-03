<?php

namespace App\Http\Middleware;

use App\Notifications\WelcomeEmail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SendFirstLoginEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->first_login) {
            // Send welcome email for first login
            Auth::user()->notify(new WelcomeEmail());
            
            // Mark as not first login anymore
            Auth::user()->update(['first_login' => false]);
        }

        return $next($request);
    }
}
