<?php

namespace App\Providers;

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
            $dkj_users = DB::select("
                SELECT users.id, users.first_name, users.last_name FROM users
                INNER JOIN department_info ON department_info.id = users.department_info_id
                INNER JOIN department_type ON department_type.id = department_info.id_dep_type
                INNER JOIN work_hours ON work_hours.id_user = users.id
                WHERE department_type.id = 1
                AND work_hours.status = 1
                AND work_hours.date = '2017-11-22'
                GROUP BY users.id
            ");

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
