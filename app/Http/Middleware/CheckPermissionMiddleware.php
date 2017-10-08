<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Privilages;

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
        $privilages_model = new Privilages;
        // Pobranie ścieżki adresu url
        $route = $request->path();
        // Pobranie informacji o stronie
        $privilages_key = $privilages_model->where('link',$route)->select('priv')->get()->toArray();

        //Usunuięcie rzutowania tablicy
        $privilages_key = $privilages_key[0]['priv'];
        $split_privilages = explode(";",$privilages_key);

        // Dla niezalogowanych oraz nie posiadających uprawnien przekieruj do strony Logowania
        // Wylogowanie i przekierowanie do strony Logowania.
        //jeśli zalogowany i strona nie jest dostępna dla każdego ->
        if(Auth::user() && $privilages_key !='*')
        {
            //Jeśli uprawnienia są niewystarczające wyloguj ze strony
            if(!in_array($request->user()->priv,$split_privilages))
            {
                Auth::logout();
                return redirect()->to('/login')->with('warning', 'Your session has expired because your account is deactivated.');
            }else
            {   // Przekieruj jeśli uprawnienia są wystarczające
                return $next($request);
            }   //Przekieryj jeśli użytkwonik jest zalogowany i chce przejść do strony ogólnej
        }else if(Auth::user() && $privilages_key =='*')
            return $next($request);
        else{   // wyloguj jeśli żadne z z rozwiązań nie jest poprawne
            Auth::logout();
            return redirect()->to('/login')->with('warning', 'Your session has expired because your account is deactivated.');

        }
    }
}
