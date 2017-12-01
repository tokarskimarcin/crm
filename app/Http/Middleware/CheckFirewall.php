<?php

namespace App\Http\Middleware;

use App\Firewall;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckFirewall
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
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $acces = Firewall::where('ip_address',$ip)->first();

        if(is_null($acces)) {
            Auth::logout();
            return redirect('login');
        }else{
            return $next($request);
        }

    }
}
