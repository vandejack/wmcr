<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $valid_user = [
            1  => 'Super Admin',
            2  => 'OSM',
            3  => 'GM/VP/PM',
            4  => 'Manager',
            5  => 'Assistant Manager',
            6  => 'Site Manager',
            7  => 'Team Leader',
            8  => 'Surveyor',
            9  => 'Helpdesk',
            10 => 'Drafter',
            11 => 'Staff',
            12 => 'Technician',
        ];

        $level = session('auth')->level_id;

        if(empty($roles) || $level == 0)
        {
            return redirect('login');
        }

        if(is_null(session('auth')->chat_id) || session('auth')->chat_id == 0 || session('auth')->chat_id == '')
        {
            return redirect('/profile');
        }

        if (in_array($valid_user[$level], $roles) || strcasecmp($roles[0], 'All') == 0)
        {
            return $next($request);
        }

        Session::put('auth-originalUrl', $request->fullUrl());

        if ($request->ajax())
        {
            return response('UNAUTHORIZED', 401);
        }
        else
        {
            Session::put('auth-originalUrl', '');
            return redirect('login');
        }
    }

}