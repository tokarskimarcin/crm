<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NotificationTypes;
use App\Department_info;
use App\Notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\CommentsNotifications;
use App\User;
use Illuminate\Support\Facades\DB;
use App\ActivityRecorder;

class NotificationController extends Controller
{
    public function addNotificationGet() {
        $notification_types = NotificationTypes::all();
        $department_info = Department_info::all();

        return view('notifications.addNewNotification')
            ->with('notification_types', $notification_types)
            ->with('department_info', $department_info);
    }

    public function addNotificationPost(Request $request) {
        $notification = new Notifications();

        $notification->user_id = Auth::user()->id;
        $notification->title = $request->title;
        $notification->content = $request->content;
        $notification->status = 1;
        $notification->notification_type_id = $request->notification_type_id;
        $notification->department_info_id = $request->department_info_id;
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        Session::flash('message_ok', "Problem zgłoszony pomyślnie!");
        return Redirect::back();

    }

    public function showNotificationGet($id) {
        $notification = Notifications::find($id);

        $user = User::find($notification->displayed_by);

        return view('notifications.showNotification')
            ->with('user', $user)
            ->with('notification', $notification);
    }

    public function showNotificationPost($id, Request $request) {
        $notification = Notifications::find($id);

        $notification->status = $request->status;
        $notification->displayed_by = Auth::user()->id;
        $notification->updated_at = date("Y-m-d H:i:s");

        if($request->status == 2) {
            $notification->data_start = date("Y-m-d H:i:s");
        } else if ($request->status == 3) {
            $stop = date("Y-m-d H:i:s");
            $notification->data_stop = $stop;

          $sec = DB::select("SELECT SEC_TO_TIME(TIME_TO_SEC('" . $stop . "') - TIME_TO_SEC(data_start) ) as time from notifications where id = ". $id);
          $notification->sec= $sec[0]->time;
        }

        $data = [
            'Edycja statusu powiadomienia o problemie' => '',
            'Id problemu' => $notification->id,
            'status' => $request->status,
        ];
        new ActivityRecorder(7, $data);

        $notification->save();
        $user = User::find($notification->displayed_by);




        Session::flash('message_ok', "Zmiany zapisano pomyślnie!");
        return Redirect::back();
    }

    public function showAllNotificationsGet($type) {
      if  ($type == 1) {
          $notifications = Notifications::where('status', '=', 1)->orderBy('notification_type_id', 'asc')->paginate(10);
      } else if ($type == 2) {
          $notifications = Notifications::where('status', '=', 2)->orderBy('notification_type_id', 'asc')->paginate(10);
      } else if ($type == 3) {
          $notifications = Notifications::where('status', '=', 3)->orderBy('notification_type_id', 'asc')->paginate(10);
      }

      return view('notifications.allNotifications')
          ->with('type', $type)
          ->with('notifications', $notifications);
    }

    public function addCommentNotificationPost($id, Request $request) {
        $comment = new CommentsNotifications();

        $comment->user_id = Auth::user()->id;
        $comment->content = $request->content;
        $comment->notification_id = $id;
        $comment->save();

        Session::flash('message_ok', "Komentarz dodany pomyślnie!");
        return Redirect::back();
    }
}
