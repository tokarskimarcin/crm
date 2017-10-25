<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Links;
use Illuminate\Support\Facades\DB;

class CheckPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // Sprawdzenie czy użytkownik posiada uprawnienie do podanej strony

    public function handle($request, Closure $next)
    {
        // Pobranie instancji modelu
        $links = new Links;
        // Pobranie ścieżki adresu url
        $route = $request->path();
        //podział ścieżki na /
        $route = explode("/", $route);
        $route= $route[0];
        // Pobranie informacji o stronie
        $link_key = DB::table('links')
            ->leftjoin('privilage_relation', 'links.id', '=', 'privilage_relation.link_id')
            ->leftjoin('privilage_user_relation', 'links.id', '=', 'privilage_user_relation.link_id')
            ->select(DB::raw('
              privilage_relation.user_type_id,
              privilage_user_relation.user_id'
            ))
            ->where('link',$route)
            ->Where(function ($query) {
                $query->orwhere('privilage_relation.user_type_id',Auth::user()->user_type_id)
                ->orwhere('privilage_user_relation.user_id',Auth::user()->id);
            })->get();

        if($link_key->isEmpty() || !Auth::user())
        {
            Auth::logout();
            return redirect()->to('/login')->with('warning', 'Your session has expired because your account is deactivated.');
        }else if(!$link_key->isEmpty() && Auth::user())
        {
            return $next($request);
        }

    }
}
