<?php

namespace App\Providers;

use App\Dkj;
use App\LinkGroups;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Department_info;
use App\User;

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

            $links = DB::table('links')
                ->leftjoin('privilage_relation', 'links.id', '=', 'privilage_relation.link_id')
                ->leftjoin('privilage_user_relation', 'links.id', '=', 'privilage_user_relation.link_id')
                ->select(DB::raw('
                  links.group_link_id,
                  links.link,
                  links.name'

                ))
                ->Where(function ($query) {
                    $query->orwhere('privilage_relation.user_type_id',Auth::user()->user_type_id)
                        ->orwhere('privilage_user_relation.user_id',Auth::user()->id);
                })->get();

            $filtered = $links->groupBy('group_link_id');
            $filtered = array_keys($filtered->toArray());
            $groups = LinkGroups::wherein('id',$filtered)->get();

            $departments_for_dkj = Department_info::whereIn('id_dep_type', [1, 2])->get();



            $dkj_users = User::whereHas('work_hours', function ($query) {
                $today = date("Y-m-d") . "%";
                $query->where('status', 1)
                ->where('date','like',$today);
            })->whereHas('department_info', function ($query) {
                $query->where('id_dep_type', 6);
            })->groupBy('id')->get();

            $view
                ->with('groups', $groups)
                ->with('departments_for_dkj', $departments_for_dkj)
                ->with('dkj_users', $dkj_users)
                ->with('links', $links);
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
