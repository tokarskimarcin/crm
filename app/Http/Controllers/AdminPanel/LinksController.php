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
    //Show Link to Edit
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


    public function adminPrivilageEdit($id,Request $request)
    {
        $url_array          = explode('/',URL::previous());
        $urlValidation      = end($url_array);
        if ($urlValidation != $id) {
            return view('errors.404');
        }
        $data                   = [];
        $link                   = Links::findOrFail($id);
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
            PrivilageRelation::where('link_id', $id)
                ->delete();
        }else{
            PrivilageRelation::where('link_id', $id)
                ->whereNotIn('user_type_id',$request->link_privilages)
                ->delete();
            foreach ($user_tab as $item) {
                PrivilageRelation::updateOrCreate(array('user_type_id'=>$item,'link_id'=>$id));
                $data['item' . $item] = 'id' . $id;
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
}
