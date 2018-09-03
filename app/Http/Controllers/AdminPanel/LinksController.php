<?php

namespace App\Http\Controllers\AdminPanel;

use App\ActivityRecorder;
use App\LinkGroups;
use App\Links;
use App\PrivilageRelation;
use App\PrivilageUserRelation;
use App\User;
use App\UserTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LinksController extends Controller
{
    /** Show linkt to edit
     * @param $linkID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminPrivilageShow($linkID)
    {
        if ($linkID == null) {
            return view('errors.404');
        }
        $link_groups    = LinkGroups::all();
        $users_type     = UserTypes::all();
        $link           = Links::
             leftjoin('link_groups', 'link_groups.id', '=', 'links.group_link_id')
            ->leftjoin('privilage_relation', 'links.id', '=', 'privilage_relation.link_id')
            ->leftjoin('privilage_user_relation', 'links.id', '=', 'privilage_user_relation.link_id')
            ->select(
              'links.id',
              'links.link',
              'links.group_link_id',
              'links.name',
              'link_groups.name as link_groups_name',
              'privilage_relation.user_type_id as relation_user_type_id'
            )
            ->where('links.id',$linkID)
            ->get();
        $link_info      = $link->first();
        return view('admin.admin_privilage_show')
            ->with('groups',$link_groups)
            ->with('link',$link)
            ->with('users_type',$users_type)
            ->with('link_info',$link_info);
    }

    /** Edit selected LINK
     * @param $id Link ID
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminPrivilageEdit($linkID,Request $request)
    {
        $url_array          = explode('/',URL::previous());
        $urlValidation      = end($url_array);
        if ($urlValidation != $linkID) {
            return view('errors.404');
        }
        $data                   = [];
        $link                   = Links::findOrFail($linkID);
        $link->link             = $request->link_adress;
        $link->name             = $request->link_name;
        $link->group_link_id    = $request->link_group;
        try{
            $link->save();
        }catch (\Exception $exception){
            Session::flash('message_error', "Problem z edycją linku, skontaktuj się z administratorem");
            return Redirect::back();
        }

        $user_tab = $request->link_privilages;
        if($request->link_privilages == null )
        {
            PrivilageRelation::where('link_id', $linkID)
                ->delete();
        }else{
            PrivilageRelation::where('link_id', $linkID)
                ->whereNotIn('user_type_id',$request->link_privilages)
                ->delete();
            foreach ($user_tab as $item) {
                PrivilageRelation::updateOrCreate(array('user_type_id'=>$item,'link_id'=>$linkID));
                $data['item' . $item] = 'id' . $linkID;
            }

        }
        $data['Zmiana uprawnień grup i użytkowników']   = '';
        $data['Link name']                              = $request->link_name;
        $data['Link adress']                            = $request->link_adress;
        $data['Link group']                             = $request->link_goup;
        new ActivityRecorder($data,16,2);
        Session::flash('message_ok', "Zmiany zapisano!");
        return Redirect::back();
    }


    /** Show view to add new link
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createLinkGet(){
        $link_groups = LinkGroups::all();
        return view('admin.create_link')
            ->with('link_groups', $link_groups);
    }

    /** Save new link
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createLinkPost(Request $request){
        $data = [];
        $link = new Links();
        $linkGroupCheck = LinkGroups::find($request->group_link_id);
        if ($linkGroupCheck == null) {
            return view('errors.404');
        }

        $data['Nazwa linku'] = $request->name;
        $data['Link'] = $request->link;
        $data['ID grupy'] = $request->group_link_id;
        $link->name = $request->name;
        $link->link = $request->link;
        $link->group_link_id = $request->group_link_id;

        try{
            $link->save();
            new ActivityRecorder($data, 72, 1);
            Session::flash('message_ok', "Link został dodany!");
            return Redirect::back();
        }catch (\Exception $exception){
            Session::flash('message_error', "Błąd wykonywania zapytania SQL.");
            return Redirect::back();
        }


        new ActivityRecorder($data, 72, 1);
        Session::flash('message_ok', "Link został dodany!");
        return Redirect::back();
    }

    /** Save new group
     * @param Request $request
     * @return mixed
     */
    public function addGroup(Request $request) {
        $data = [];
        $newGroupName = trim($request->addLinkGroup, ' ');
        $newGroup = new LinkGroups();
        $data['Nazwa dodanej grupy'] = $newGroupName;
        $newGroup->name = $newGroupName;
        try {
            $newGroup->save();
            new ActivityRecorder($data, 72, 1);
            Session::flash('message_ok', "Grupa został dodana");
            return Redirect::back();
        }catch (\Exception $exception){
            Session::flash('message_error', "Błąd wykonywania zapytania SQL.");
            return Redirect::back();
        }

    }

    /** Remove group
     * @param Request $request
     * @return mixed
     */
    public function removeGroup(Request $request) {
        $data = [];
        $groupID = $request->removeLinkGroup;
        $data['ID grupy'] = $groupID;
        $groupToDelete = LinkGroups::where('id', '=', $groupID)->first();
        try {
            $groupToDelete->delete();
            new ActivityRecorder($data, 72, 3);
            Session::flash('message_ok', "Grupa został usunięta");
            return Redirect::back();
        }catch (\Exception $exception){
            Session::flash('message_error', "Błąd wykonywania zapytania SQL.");
            return Redirect::back();
        }

    }

    /** Show view to add or remove privilages
     * @return mixed
     */
    public function userPrivilagesGET() {
        $all_users = User::all();
        $all_privilage_users = PrivilageUserRelation::all();
        $all_links = Links::select('id', 'name')
            ->get();
        return view('admin.userPrivilage')->with('all_users', $all_users)->with('all_privilage_users', $all_privilage_users)->with('all_links', $all_links);
    }

    /** get Datatable info to view userPrivilages ['Left column']
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function userPrivilage(Request $request) {
        $privilage_people = $request->privilage_people;
        $all_users = User::select(
                'users.id as user_id',
                'users.first_name as first_name',
                'users.last_name as last_name')
            ->where('users.status_work', '=', 1);
        if($privilage_people == "true") { //checkbox is checked
            $all_users = $all_users
                ->join('privilage_user_relation', 'users.id', '=', 'privilage_user_relation.user_id')
                ->distinct('user_id');
        }
        return datatables($all_users->get())->make(true);
    }

    /** Get info with link is avaible for selected user
     * @param Request $request
     * @return mixed
     */
    public function userPrivilagesAjaxData(Request $request) {
        $user_id = $request->id_of_user;
        $all = PrivilageUserRelation::
            select(
                'privilage_user_relation.link_id as link_id',
                'links.name')
            ->join('links', 'privilage_user_relation.link_id', 'links.id')
            ->where('privilage_user_relation.user_id', '=', $user_id)
            ->get();
        return $all;
    }

    /** Save and remove privilages
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userPrivilagesPOST(Request $request) {
        $data = [];
        $remove_id = $request->remove_privilage_id; //link_id
        $user_id = $request->user_id; //user_id
        $adding = $request->isAdding;
        if($adding == 'false') {
            if(!(is_null($remove_id) || is_null($user_id))) {
                try{
                    PrivilageUserRelation::
                    where('user_id', '=', $user_id)
                        ->where('link_id', '=', $remove_id)
                        ->delete();
                    $data['ID użytkownika'] = $user_id;
                    $data['ID linku'] = $remove_id;
                    new ActivityRecorder($data,191,3);
                    Session::flash('message_ok', "Uprawnienia dodane!");
                }catch (\Exception $exception){
                    Session::flash('message_error', "Problem wykonania SQL Line: 257");
                    redirect()->back();
                }
            }
        }
        else {
            $new_privilage_number = $request->add_new_privilage; // link_id
            $new_privilage = new PrivilageUserRelation();
            $new_privilage->link_id = $new_privilage_number;
            $new_privilage->user_id = $user_id;
            try {
                $new_privilage->save();
                $data['ID użytkownika'] = $user_id;
                $data['ID linku'] = $new_privilage_number;
                new ActivityRecorder($data,191,1);
            }catch (\Exception $exception){
                Session::flash('message_error', "Problem wykonania SQL Line: 273");
                redirect()->back();
            }
        }
        return redirect()->back();

    }
}
