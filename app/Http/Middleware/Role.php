<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {

        $newRol = explode('|',$roles);

        $roleName = strtolower($request->user()->role->name);

        if (!in_array($roleName,$newRol))
            return abort(403,__('Unauthorized'));

        return $next($request);
    }
}
