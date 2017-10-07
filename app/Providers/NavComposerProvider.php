<?php

namespace App\Providers;

use App\Link_groups;
use App\Privilages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class NavComposerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Pobranie zaladek o navbaru zaleznie od uprawnien
        view()->composer('partials._nav', function ($view) {

            $links = Privilages::where('priv', 'like', '%,'.Auth::user()->user_type_id . ',%')
                ->orWhere('priv', 'like', '*')
                ->get()->toArray();

            $view->with('group', Link_groups::All()->toArray())->with('links', $links);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
