<?php

namespace App\Http\Controllers\AdminPanel;

use App\ActivityRecorder;
use App\Firewall;
use App\FirewallPrivileges;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class FireWallController extends Controller
{
    /** Get firewall view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function firewallGet() {
        $firewall = Firewall::all();
        return view('admin.firewall')
            ->with('firewall', $firewall);
    }

    /**
     *  Add new ip to firewall
     * @param Request $request
     * @return mixed
     */
    public function firewallPost(Request $request) {
        $data = [];
        $firewallSotry =  Firewall::where('ip_address',$request->new_ip)->first();
        if(empty($firewallSotry)){
            $firewall = new Firewall();
        }else{
            $firewall = $firewallSotry;
        }
        $data['IP'] = $request->new_ip;
        $firewall->ip_address = $request->new_ip;
        $firewall->whitelisted = 1;
        try{
            $firewall->save();
            new ActivityRecorder($data, 88,1);
            Session::flash('message_ok', "Adres IP został dodany!");
            return Redirect::back();
        }catch (\Exception $exception){
            Session::flash('message_error', "Błąd wykonywania SQL  Line : 47");
            return Redirect::back();
        }
    }

    /** Show user with disabled firewall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function firewallPrivilegesGet() {
        $firewall_privileges = FirewallPrivileges::all();
        $users = User::onlyCadre()->activeUser()->orderBy('last_name')->get();

        return view('admin.firewallPrivileges')
            ->with('firewall_privileges', $firewall_privileges)
            ->with('users', $users);
    }

    /** Save new privalages for user
     * @param Request $request
     * @return mixed
     */
    public function firewallPrivilegesPost(Request $request) {
        $data = [];
        $obj = new FirewallPrivileges();

        $data['ID użytkownika'] = $request->user_selected;
        $obj->user_id = $request->user_selected;
        try{
            $obj->save();
            new ActivityRecorder($data, 89, 1);
            Session::flash('message_ok', "Użytkownik został dodany!");
            return Redirect::back();
        }catch (\Exception $exception){
            Session::flash('message_error', "Błąd wykonywania SQL Line : 81");
            return Redirect::back();
        }

    }

    /** Remove user from firewallList
     * @param Request $request
     * @return int
     */
    public function firewallDeleteUser(Request $request) {
        if ($request->ajax()) {
            $user = User::find($request->user_id);
            if ($user == null) {
                return 0;
            } else {
                try{
                    FirewallPrivileges::where('user_id', '=', $request->user_id)->delete();
                    $data['ID użytkownika'] = $request->user_id;
                    $data['Użytkownik'] = $user->first_name.' '.$user->last_name;
                    new ActivityRecorder($data,89,3);
                    return 1;
                }catch (\Exception $exception){
                    return 0;
                }
            }
        }
    }
}
