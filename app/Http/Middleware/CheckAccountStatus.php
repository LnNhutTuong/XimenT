<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && !auth()->user()->status){
            auth()->logout();
            return redirect()->route('login')->with('error', 
            'Tài khoản của bạn đã bị khóa vì lý do nào đó...');
        }
        return $next($request);
    }
}
