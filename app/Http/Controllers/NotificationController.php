<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NotificationTypes;
use App\Department_info;
use App\Notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;

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
        $notification->notification_type_id = $request->notification_type_id;
        $notification->department_info_id = $request->department_info_id;
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        Session::flash('message_ok', "Problem zgłoszony pomyślnie!");
        return Redirect::back();

    }

    public function showNotificationGet($id) {
        $notification = Notifications::find($id);

        return view('notifications.showNotification')
            ->with('notification', $notification);
    }

    public function showAllNotifications() {

      //tutaj dodac widok dla wsyzstkich zgłoszeń
      //zmienic strukture bazy dancyh
      return 100;
    }
}
