<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Company;
class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        if($role == 'create' && (!Company::where('owner', Auth::user()->id)->first())){
            return Response::json(['error'=>'Access denied'],403);
        }elseif($role == 'edit'){
            $user = User::find($request->route('id'));
            if($request->route('id') != Auth::user()->id && !Company::where(['owner'=> Auth::user()->id,'id'=>$user->company_id])->first()){
                return Response::json(['error' => 'Access denied'], 403);
            }
        }elseif($role == 'delete' || $role == 'status'){

            $user = User::find($request->route('id'));
            if($user->id == Auth::user()->id || !Company::where(['owner'=> Auth::user()->id,'id'=>$user->company_id])->first()){
                return Response::json(['error' => 'Access denied'], 403);
            }
        }

        return $next($request);
    }
}
