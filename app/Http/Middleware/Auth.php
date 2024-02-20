<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;
use App\Models\LoginModel;
use Closure;

class Auth
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
        $rememberToken = $request->cookie('wmcr-token');

        if ($rememberToken)
        {
            $user = LoginModel::getByToken($rememberToken);
            if ($user)
            {
                Session::put('auth', $user);
            }
        }
        Session::put('auth-originalUrl', $request->fullUrl());
        if (!Session::has('auth'))
        {
            if ($request->ajax())
            {
                return response('UNAUTHORIZED', 401);
            }
            else
            {
                return redirect('login');
            }
        }
        return $next($request);
    }
}
?>