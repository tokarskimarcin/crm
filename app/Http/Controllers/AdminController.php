<?php

namespace App\Http\Controllers;

use App\LinkGroups;
use App\UserTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function admin_privilage()
    {
        $link_groups = LinkGroups::all();
        $links =  DB::table('links')
            ->leftjoin('link_groups', 'link_groups.id', '=', 'links.group_link_id')
            ->select(DB::raw('
              links.id,
              links.link,
              links.name,
              link_groups.name as link_groups_name'
            ))
            ->get();

        return view('admin.admin_privilage')
            ->with('groups',$link_groups)
            ->with('links',$links);
    }
    public function admin_privilage_show($id)
    {
        $link_groups = LinkGroups::all();
        $users_type = UserTypes::all();
        $links =  DB::table('links')
            ->leftjoin('link_groups', 'link_groups.id', '=', 'links.group_link_id')
            ->leftjoin('privilage_relation', 'links.id', '=', 'privilage_relation.link_id')
            ->leftjoin('privilage_user_relation', 'links.id', '=', 'privilage_user_relation.link_id')
            ->select(DB::raw('
              links.id as id,
              links.link,
              links.name,
              link_groups.name as link_groups_name'
            ))
            ->where('links.id',$id)
            ->get();

        return view('admin.admin_privilage_show')
            ->with('groups',$link_groups)
            ->with('links',$links)
            ->with('users_type',$users_type);
    }
}
