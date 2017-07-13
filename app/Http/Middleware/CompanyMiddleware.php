<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(!Company::where(['owner'=> Auth::user()->id])->first()){
            return Response::json(['error' => 'Access denied'], 403);
        }

        return $next($request);
    }
}
