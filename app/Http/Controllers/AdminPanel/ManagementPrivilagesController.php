<?php

namespace App\Http\Controllers\AdminPanel;

use App\Department_info;
use App\Department_types;
use App\Departments;
use App\LinkGroups;
use App\Links;
use App\Http\Controllers\Controller;

class ManagementPrivilagesController extends Controller
{
    //Show admin panel
    public function adminPrivilage()
    {
        $link_groups = LinkGroups::all();
        $Department_types = Department_types::all();
        $Department_info = Department_info::all();
        $Departments = Departments::all();
        $links = Links::
        leftjoin('link_groups', 'link_groups.id', '=', 'links.group_link_id')
            ->select(
                'links.id',
                'links.link',
                'links.name',
                'link_groups.name as link_groups_name'
            )->get();

        return view('admin.admin_privilage')
            ->with('groups',$link_groups)
            ->with('links',$links)
            ->with('department_types',$Department_types)
            ->with('department_info',$Department_info)
            ->with('departments',$Departments);
    }
}
