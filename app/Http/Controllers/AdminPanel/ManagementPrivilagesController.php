<?php

namespace App\Http\Controllers\AdminPanel;

use App\ActivityRecorder;
use App\Department_info;
use App\Department_types;
use App\Departments;
use App\LinkGroups;
use App\Links;
use App\PrivilageUserRelation;
use App\User;
use App\UserTypes;
use App\PrivilageRelation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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

    /**
     * @param Request $request
     * @return mixed
     */
    public function assignPrivilagesPOST(Request $request) {
        if($request->has('type')) {
            $successMessage = null;
            $type = $request->type;

            try {
                switch($type) {
                    case 1: { // role -> role
                        $from = (int) $request->role_first_role;

                        if($from !== 0) { //correct value
                            $to = (int) $request->role_second_role;

                            if($to !== 0) { //correct value
                                $this->assignPrivilagesRoles($from, $to);
                                new ActivityRecorder(array_merge(['T' => 'Przepisanie uprawnień między rolami'],['Z Roli: ' .$from => 'Na role: ' . $to]),264, 1);
                                $successMessage = 'Dostępy z roli o id '. $from . ' zostały przypisane do roli o id ' . $to;
                            }
                            else { //default value
                                Throw new \Exception('Wybierz rolę, na którą chcesz przepisać dostępy');
                            }
                        }
                        else { //Default value
                            Throw new \Exception('Wybierz role z której chcesz przepisać dostępy!');
                        }
                        break;
                    }
                    case 2: { //role -> user
                        $from = (int) $request->mixed_first_role;

                        if($from !== 0) { //correct value
                            $to = (int) $request->mixed_second_users;

                            if($to !== 0) { //correct value
                                $this->assignPrivilagesMixed($from, $to);
                                new ActivityRecorder(array_merge(['T' => 'Przepisanie uprawnień z roli na użytkownika'],['Z Roli: ' .$from => 'Na użytkownika: ' . $to]),264, 1);
                                $successMessage = 'Dostępy z roli o id '. $from .' zostały przepisane dla użytkownika o id ' . $to;
                            }
                            else { //default value
                                Throw new \Exception('Wybierz użytkownika, na którego chcesz przepisać dostępy');
                            }
                        }
                        else { //Default value
                            Throw new \Exception('Wybierz role z której chcesz przepisać dostępy!');
                        }
                        break;
                    }
                    case 3: { //user -> user
                        $from = (int) $request->users_first_user;

                        if($from !== 0) { //correct value
                            $to = (int) $request->users_second_user;

                            if($to !== 0) { //correct value
                                $this->assignPrivilagesUsers($from, $to);
                                new ActivityRecorder(array_merge(['T' => 'Przepisanie uprawnień między użytkownikami'],['Z użytkownika: ' .$from => 'Na użytkownika: ' . $to]),264, 1);
                                $successMessage = 'Dostępy z użytkownika o id '. $from . ' zostały przypisane na użytkownika o id ' . $to;
                            }
                            else { //default value
                                Throw new \Exception('Wybierz użytkownika, na którego chcesz przepisać dostępy');
                            }
                        }
                        else { //Default value
                            Throw new \Exception('Wybierz użytkownika, z którego chcesz przepisać uprawniania!');
                        }
                        break;
                    }
                    default: {
                        Throw new \Exception('Wrong form type');
                        break;
                    }
                }
            }
            catch(\Exception $error) {
                return back()->withError($error->getMessage());
            }

            $request->session()->flash('successMessage', $successMessage);
            return Redirect::back();
        }
    }

    /**
     * @param $from - User_type_id
     * @param $to - User_type_id
     * This method assigns all links from role: $from, to role: $to.
     */
    private function assignPrivilagesRoles($from, $to) {
        $privilages = PrivilageRelation::where('user_type_id', '=', $from)->pluck('link_id')->toArray();

        foreach($privilages as $privilage) {
            if(PrivilageRelation::where('user_type_id', '=', $to)->where('link_id', '=', $privilage)->get()->isEmpty()) {
                $priv = new PrivilageRelation();
                $priv->user_type_id = $to;
                $priv->link_id = $privilage;
//                $priv->save();
            }
        }
    }

    /**
     * @param $from - user_type_id
     * @param $to - user_id
     * This method assigns all links from role: $from, to user: $to
     */
    private function assignPrivilagesMixed($from, $to) {
        $privilages = PrivilageRelation::where('user_type_id', '=', $from)->pluck('link_id')->toArray();

        foreach($privilages as $privilage) {
            if(PrivilageUserRelation::where('user_id', '=', $to)->where('link_id', '=', $privilage)->get()->isEmpty()) {
                $priv = new PrivilageUserRelation();
                $priv->link_id = $privilage;
                $priv->user_id = $to;
//                $priv->save();
            }
        }
    }

    /**
     * @param $from - user_id
     * @param $to - user_id
     * This method assigns all links from user: $from, to user: $to
     */
    private function assignPrivilagesUsers($from, $to) {
        $privilages = PrivilageUserRelation::where('user_id', '=', $from)->pluck('link_id')->toArray();

        if(PrivilageUserRelation::where('user_id', '=', $from)->get()->isEmpty()) {
            Throw new \Exception('Użytkownik nie ma żadnych dostępów, które mogą być przepisane.');
        }

        foreach($privilages as $privilage) {
            if(PrivilageUserRelation::where('user_id', '=', $to)->where('link_id', '=', $privilage)->get()->isEmpty()) {
                $priv = new PrivilageUserRelation();
                $priv->link_id = $privilage;
                $priv->user_id = $to;
//                $priv->save();
            }
        }
    }
}
