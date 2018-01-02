<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Links;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Session;

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

        if(Auth::check()) {
            //Sprawdzenie czy pracownik jest zatrudniony
            if (Auth::user()->status_work == 0) {
                Auth::logout();
                Session::flash('message', 'Twoje konto jest nieaktywne!');
                return redirect('/login');
            }

            $expired_date = date('Y-m-d', strtotime("+3 months", strtotime(Auth::user()->password_date)));
            // Pobranie instancji modelu
            $links = new Links;
            // Pobranie ścieżki adresu url
            $route = $request->path();
            //podział ścieżki na /
            $route = explode("/", $route);
            $route = $route[0];
            // Pobranie informacji o stronie
            $link_key = DB::table('links')
                ->leftjoin('privilage_relation', 'links.id', '=', 'privilage_relation.link_id')
                ->leftjoin('privilage_user_relation', 'links.id', '=', 'privilage_user_relation.link_id')
                ->select(DB::raw('
              privilage_relation.user_type_id,
              privilage_user_relation.user_id'
                ))
                ->where('link', $route)
                ->Where(function ($query) {
                    $query->orwhere('privilage_relation.user_type_id', Auth::user()->user_type_id)
                        ->orwhere('privilage_user_relation.user_id', Auth::user()->id);
                })->get();




            if (Auth::user()->department_info->blocked != 0) { // gdy jest inspecja pracy
                if ($route != '') { // nie pozwalaj do innej strony niz domowa
                  Auth::logout();
                  return redirect()->to('/login')->with('message', 'Nastąpiło przelogowanie');
                }else
                {
                    return $next($request);
                }
            }

            if($expired_date <= date('Y-m-d') && $route == 'password_change')
            {
                return $next($request);
            }

            if($expired_date > date('Y-m-d')) {
                if ($route == '') // pozwól do storny głównej
                {
                    return $next($request);
                }
                if (!$link_key->isEmpty() && Auth::user()) { // gdy jest dostęp
                    return $next($request);
                }
            }else
            {
                return redirect()->route('changePassword');
            }

            if ($link_key->isEmpty() || !Auth::user()) { // gdy brak dostępu
                Auth::logout();
                return Redirect::to('/login')->with('message', 'Brak Dostępu.');
            }


        }else{ // Brak sesji zalogowania
            return redirect()->to('/login')->with('message', 'Brak Sesji.');
        }

    }
}
