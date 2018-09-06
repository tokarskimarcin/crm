<?php

namespace App\Http\Controllers;

use App\DoublingQueryLogs;
use App\NotificationChangesDisplayedFlags;
use App\Work_Hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Notifications;
use App\MultipleDepartments;

class HomeController extends Controller
{
    private $actuall_date;
    private $actuall_hour;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->actuall_date = date("Y-m-d");
        $this->actuall_hour = date("H:i:s");
    }


    public function index()
    {
        if ($this->checkStatusWork() == 3 || $this->checkStatusWork() == 4) {
            $register_start = Work_Hour::where('date',$this->actuall_date)
                ->where('id_user',Auth::id())
                ->pluck('register_start')
                ->first();

            $register_stop = Work_Hour::where('date',$this->actuall_date)
                ->where('id_user',Auth::id())
                ->pluck('register_stop')
                ->first();
        } else {
            $register_start = 0;
            $register_stop = 0;
        }

        return view('home.index')
        ->with('status',$this->checkStatusWork())
        ->with('register_start', $register_start)
        ->with('register_stop', $register_stop);
    }

    public function startWork(Request $request)
    {
        if($request->ajax() && $this->checkStatusWork() == 0)
        {
            $work_hour = new Work_Hour;
            $work_hour->status = 1;
            $work_hour->accept_sec = 0;
            $work_hour->success = 0;
            $work_hour->date = $this->actuall_date;
            $work_hour->click_start = $this->actuall_hour;
            $work_hour->id_user = Auth::id();
            $work_hour->created_at = date('Y-m-d H:i:s');

            if(session()->has('isWork_HourQueryRunning')){
                if(session('isWork_HourQueryRunning')){
                    $DOUBLING_QUERY_LOG = new DoublingQueryLogs();
                    $DOUBLING_QUERY_LOG->table_name = 'Work_Hour';
                    $DOUBLING_QUERY_LOG->save();
                }else{
                    session(['isWork_HourQueryRunning' => true]);
                    $work_hour->save();
                    session()->forget('isWork_HourQueryRunning');
                }
            }else{
                session(['isWork_HourQueryRunning' => true]);
                $work_hour->save();
                session()->forget('isWork_HourQueryRunning');
            }

            return 'success';
        }else{
            return 'fail';
        }
    }

    public function stopWork(Request $request)
    {
        if($request->ajax() && $this->checkStatusWork() == 1)
        {
            Work_Hour::where('id_user', Auth::id())
                ->where('date',$this->actuall_date)
                ->update(['status' => 2,'click_stop' => $this->actuall_hour, 'updated_at' => date('Y-m-d H:i:s')]);
            return 'success';
        }else{
            return 'fail';
        }
    }

    public function checkStatusWork()
    {
        try{
            $status = Work_Hour::
                select('status')
                ->where('date','like',$this->actuall_date)
                ->where('id_user','=',Auth::user()->id)
                ->first();
            if(empty($status)){
                return 0;
            }
            return $status->status;
        }catch(\Exception $e){
            return -1;
        }
    }
    public function admin()
    {
        return view('admin');
    }

    public function changeDepartment(Request $request) {
        if($request->ajax()) {
            $user = User::find(Auth::user()->id);
            $access = false;
            $multiple_departments = MultipleDepartments::all();
            foreach($multiple_departments as $department) {
                if ($department->user_id == $user->id) {
                    if ($department->department_info_id == $request->department_info_id) {
                        $access = true;
                    }
                }
            }
            if ($access === true) {
                $user->department_info_id = $request->department_info_id;
                $user->save();
                return 1;
            }
        }
    }

    public function itSupport(Request $request) {
        if($request->ajax()) {
            $notifications = Notifications::with('user')
            ->with('notification_type')
                ->where('user_id','<>',Auth::user()->id)
                ->where('status', 1)->orderBy('notification_type_id', 'asc')->get();

            return $notifications;
        }
    }

    public function itSupportNotRepairedNotifications(Request $request) {
        if($request->ajax()) {
            $notRepairedNotifications = Notifications::where('status','=',2)->where('displayed_by', Auth::user()->id)->count();
            return $notRepairedNotifications;
        }
    }

    public function cadreSupportUnratedNotifications(Request $request) {
        if($request->ajax()) {
            $unratedNotifications = Notifications::select('status','jr.id')
                ->leftJoin('judge_results as jr','jr.notification_id','=','notifications.id')
                ->where('notifications.user_id','=',Auth::user()->id)
                ->where('status','=',3)
                ->whereNull('jr.id')
                ->count();
            return $unratedNotifications;
        }
    }

    public function itCountNotifications(Request $request) {
        if($request->ajax()) {
            $notifications_count = Notifications::where('status', 1)
                ->where('user_id','<>',Auth::user()->id)->count();

            return $notifications_count;
        }
    }

    public function cadreSupport(Request $request) {
        if($request->ajax()) {
            $notifications = Notifications::where(function ($query){
                $query->where('user_id',Auth::user()->id)
                ->where(function ($querySecond){
                    $querySecond->where('status_change_displayed','<>',1)->orWhere('comment_added_by_realizator_displayed','<>',1);
                });
            })->orWhere(function ($query){
                $query->where('displayed_by',Auth::user()->id)->where('comment_added_by_reporter_displayed','<>',1);})
                ->select('notifications.*')
                ->rightJoin('notifications_changes_displayed_flags as ncdf','ncdf.notification_id','=','notifications.id')
                ->with('notifications_changes_displayed_flags')
                ->with('comments')
                ->with('user')
                ->get();

            /*->where(function ($query){
                $query->where('status_change_displayed','<>',1)
                    ->orWhere('comment_added_by_realizator_displayed','<>',1)
                    ->orWhere('comment_added_by_reporter_displayed','<>',1);})*/
            return $notifications;
        }
    }
    public function cadreCountNotifications(Request $request) {
        if($request->ajax()) {
            $notifications = Notifications::where(function ($query){
                $query->where('user_id',Auth::user()->id)
                    ->where(function ($querySecond){
                        $querySecond->where('status_change_displayed','<>',1)->orWhere('comment_added_by_realizator_displayed','<>',1);
                    });
            })->orWhere(function ($query){
                $query->where('displayed_by',Auth::user()->id)->where('comment_added_by_reporter_displayed','<>',1);})
                ->select('notifications.*')
                ->rightJoin('notifications_changes_displayed_flags as ncdf','ncdf.notification_id','=','notifications.id')
                ->with('notifications_changes_displayed_flags')
                ->get();
            $counter = 0;
            foreach($notifications as $notification){
                if($notification->user_id == Auth::user()->id){
                    if($notification->notifications_changes_displayed_flags->comment_added_by_realizator_displayed == 0){
                        $counter++;
                    }
                    if($notification->notifications_changes_displayed_flags->status_change_displayed == 0){
                        $counter++;
                    }
                }
                if($notification->displayed_by == Auth::user()->id ){
                    if($notification->notifications_changes_displayed_flags->comment_added_by_reporter_displayed == 0){
                        $counter++;
                    }
                }
            }
            return $counter;
        }
    }

    /**
     * Pobieranie powiadomień bootstrapowych 
     */
    public function getBootstrapNotifications(Request $request) {
        if ($request->ajax()) {
            return 1;
        }
    }
}
