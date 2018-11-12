<?php

namespace App\Http\Controllers\AdminPanel;

use App\Department_info;
use App\Department_types;
use App\Departments;
use App\LinkGroups;
use App\Links;
use App\User;
use App\UserTypes;
use App\PrivilageRelation;
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


    //This method returns view assignPrivilages with necessary data.
    public function assignPrivilagesGET() {
      $activeUsers = User::getActiveUsers();
      $allRoles = UserTypes::all();

      return view('admin.assignPrivilages')
        ->with('activeUsers', $activeUsers)
        ->with('allRoles', $allRoles);
    }

    public function assignPrivilagesPOST(Request $request) {
      if($request->has('type')) {
        $type = $request->type;

        switch($type) {
          case 1: {
              $from = $request->role-first-role;

              if($from !== 0) { //correct value
                $to = $request->role-second-role;

                if($to !== 0) { //correct value
                   $privilages = PrivilageRelation::where('user_type_id', '=', $from)->pluck('link_id')->toArray();

                   foreach($privilages as $privilage) {
                       if(PrivilageRelation::where('user_type_id', '=', $to)->where('link_id', '=', $privilage)->get()->isEmpty()) {
                           $priv = new PrivilageRelation();
                           $priv->user_type_id = $to;
                           $priv->link_id = $privilage;
                           dd('Dziala');
                           // $priv->save();
                       }
                   }
                }
                else { //default value

                }
              }
              else { //Default value
                Throw new \Exception('Wybierz role!');
              }
            break;
          }
          case 2: {

            break;
          }
          case 3: {

            break;
          }
          default: {
            Throw new \Exception('Nie ma takiego typu');
            break;
          }
        }
      }
    }
}
