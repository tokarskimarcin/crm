<?php

namespace App\Http\Controllers\AdminPanel;

use App\ActivityRecorder;
use App\LinkGroups;
use App\Links;
use App\PrivilageRelation;
use App\UserTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
}
