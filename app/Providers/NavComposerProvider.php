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

            $links = Privilages::where('priv', 'like', '%;'.Auth::user()->user_type_id . ';%')
                ->orWhere('priv', 'like', '*')
                ->get();
            $filtered = $links->groupBy('group_link_id');
            $filtered = array_keys($filtered->toArray());
            $groups = Link_groups::wherein('id',$filtered)->get();


            $view->with('groups', $groups)->with('links', $links);
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
