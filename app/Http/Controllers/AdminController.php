<?php

namespace App\Http\Controllers;

use App\LinkGroups;
use App\Links;
use App\PrivilageRelation;
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
        $link =  DB::table('links')
            ->leftjoin('link_groups', 'link_groups.id', '=', 'links.group_link_id')
            ->leftjoin('privilage_relation', 'links.id', '=', 'privilage_relation.link_id')
            ->leftjoin('privilage_user_relation', 'links.id', '=', 'privilage_user_relation.link_id')
            ->select(DB::raw('
              links.id as id,
              links.link,
              links.group_link_id,
              links.name,
              link_groups.name as link_groups_name,
              privilage_relation.user_type_id as relation_user_type_id'
            ))
            ->where('links.id',$id)
            ->get();
        $link_info = $link->first();

        return view('admin.admin_privilage_show')
            ->with('groups',$link_groups)
            ->with('link',$link)
            ->with('users_type',$users_type)
            ->with('link_info',$link_info);
    }
    public function admin_privilage_edit($id,Request $request)
    {
            $link = Links::findOrFail($id);
            $link->link = $request->link_adress;
            $link->name = $request->link_name;
            $link->group_link_id = $request->link_group;
            $link->save();
            $user_tab = $request->link_privilages;
            if($request->link_privilages == null )
            {
                PrivilageRelation::where('link_id', $id)
                ->delete();
            }else{
                PrivilageRelation::where('link_id', $id)
                ->wherenotin('link_id',$request->link_privilages)
                ->delete();
                foreach ($user_tab as $item)
                    PrivilageRelation::updateOrCreate(array('user_type_id'=>$item,'link_id'=>$id));
            }
        return redirect('/admin_privilage');
    }
}
