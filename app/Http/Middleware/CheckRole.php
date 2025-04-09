<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @param string $role
     */
    public function handle(Request $request, Closure $next, $role): Response
    {


        if(Auth::check())
        {

            if(Auth::user()->role === $role)
            {
                return $next($request);
            }

        }
        return redirect('/'); 
        
    }
}
