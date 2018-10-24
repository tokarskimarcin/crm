<?php

namespace App\Http\Controllers;

use App\NotificationAbout;
use App\NotificationChangesDisplayedFlags;
use App\NotificationRating;
use App\NotificationRatingComponents;
use App\NotificationRatingCriterion;
use App\Utilities\NumbersProcessing\Normalizer;
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
use Illuminate\Support\Facades\URL;

class NotificationController extends Controller
{
    public function addNotificationGet() {
        $notification_types = NotificationTypes::all();
        $notification_abouts = NotificationAbout::all();
        $department_info = Department_info::all();

        return view('notifications.addNewNotification')
            ->with('notification_types', $notification_types)
            ->with('notification_abouts', $notification_abouts)
            ->with('department_info', $department_info);
    }

    public function addNotificationPost(Request $request) {
        $notification = new Notifications();

        $departmentCheck = Department_info::find($request->department_info_id);
        $notificationCheck = NotificationTypes::find($request->notification_type_id);

        if ($departmentCheck == null || $notificationCheck == null) {
            return view('errors.404');
        }

        $notification->user_id = Auth::user()->id;
        $notification->title = $request->title;
        $notification->content = $request->content;
        $notification->status = 1;
        $notification->notification_type_id = $request->notification_type_id;;
        $notification->sticker_number = $request->stickerNr == '' ? null : $request->stickerNr;
        $notification->notification_about_id = $request->notification_about_id;
        $notification->department_info_id = $request->department_info_id;
        $notification->created_at = date("Y-m-d H:i:s");
        $notification->save();

        $notification_changes_displayed_flags = new NotificationChangesDisplayedFlags();
        $notification_changes_displayed_flags->notification_id = $notification->id;
        $notification_changes_displayed_flags->comment_added_by_reporter_displayed = true;
        $notification_changes_displayed_flags->comment_added_by_realizator_displayed = true;
        $notification_changes_displayed_flags->status_change_displayed = true;
        $notification_changes_displayed_flags->save();

        new ActivityRecorder(array_merge(['T'=>'Dodanie nowego zgłoszenia problemu'],$notification->toArray()), 35, 1);
        Session::flash('message_ok', "Problem zgłoszony pomyślnie!");
        return Redirect::back();

    }

    public function showNotificationGet($id) {
        $notification = Notifications::where('id',$id)->with('notification_about')->first();

        if ($notification == null) {
            return view('errors.404');
        } else {
            $user = User::find($notification->displayed_by);

            $notificationChangesDisplayedFlags = NotificationChangesDisplayedFlags::where('notification_id','=',$notification->id)->first();
            if(!empty($notificationChangesDisplayedFlags)){
                $notificationChangesDisplayedFlags->comment_added_by_reporter_displayed = true;
                $notificationChangesDisplayedFlags->save();
            }

            return view('notifications.showNotification')
                ->with('user', $user)
                ->with('notification', $notification);
        }


    }

    public function showNotificationPost($id, Request $request) {
        $notification = Notifications::find($id);
        $url_array = explode('/',URL::previous());
        $urlValidation = end($url_array);
        if ($urlValidation != $id) {
            return view('errors.404');
        }

        if ($notification == null) {
            return view('errors.404');
        } else {
            $status = $request->status;
            $default_array = [1,2,3];
            if (!in_array($status, $default_array)) {
                return view('errors.404');
            }
            if($notification->status != $status){
                $notificationChangesDisplayedFlags = NotificationChangesDisplayedFlags::where('notification_id','=',$notification->id)->first();
                if(!empty($notificationChangesDisplayedFlags)){
                    $notificationChangesDisplayedFlags->status_change_displayed = false;
                    $notificationChangesDisplayedFlags->save();
                }
            }
            $notification->status = $status;
            $notification->displayed_by = Auth::user()->id;
            $notification->updated_at = date("Y-m-d H:i:s");

            if($request->status == 2) {
                $notification->data_start = date("Y-m-d H:i:s");
            } else if ($request->status == 3) {
                $stop = date("Y-m-d H:i:s");
                $notification->data_stop = $stop;

                $date_start =\DateTime::createFromFormat('Y-m-d H:i:s',$notification->data_start);
                $date_stop = \DateTime::createFromFormat('Y-m-d H:i:s',$notification->data_stop);
                $interval = $date_start->diff($date_stop);
                $clockTimeDuration = ($interval->h < 10 ? '0'.$interval->h : $interval->h)
                    .':'.($interval->i < 10 ? '0'.$interval->i : $interval->i)
                    .':'.($interval->s < 10 ? '0'.$interval->s : $interval->s);

                $notification->sec= $clockTimeDuration;
                $notification->realization_days_duration = $interval->d + $interval->m*30; //estimation
            }

            $notification->save();
            new ActivityRecorder(array_merge(['T'=>'Edycja statusu powiadomienia o problemie'],$notification->toArray()),36,2);

            $user = User::find($notification->displayed_by);

            Session::flash('message_ok', "Zmiany zapisano pomyślnie!");
            return Redirect::back();
        }
    }

  public function showAllNotificationsGet() {
      return view('notifications.allNotifications');
    }

    private function datatableDataForNotifications($type) {
        $data = DB::table('notifications')
            ->select(DB::raw('
                notifications.id as notification_id,
                notifications.title,
                notifications.created_at,
                departments.name as dep_name,
                department_type.name as dep_name_type,
                data_start,
                data_stop,
                u.first_name,
                u.last_name,
                CONCAT(u1.first_name, " ", u1.last_name) as displayedBy
            '))
            ->join('users as u', 'u.id', '=', 'notifications.user_id')
            ->leftJoin('users as u1', 'u1.id', '=', 'notifications.displayed_by')
            ->join('department_info', 'department_info.id', '=', 'notifications.department_info_id')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->where('notifications.status', '=', $type)
            ->get();

        return $data;
    }

    public function datatableShowNewNotifications(Request $request){
            $data = $this->datatableDataForNotifications(1);

            return datatables($data)->make(true);
    }

    public function datatableShowInProgressNotifications(Request $request){
        $data = $this->datatableDataForNotifications(2);

        return datatables($data)->make(true);
    }
    public function datatableShowFinishedNotifications(Request $request){
        $data = $this->datatableDataForNotifications(3);

        return datatables($data)->make(true);
    }

    public function addCommentNotificationPost($id, Request $request) {
        $comment = new CommentsNotifications();

        $url_array = explode('/',URL::previous());
        $urlValidation = end($url_array);
        $checkNotification = Notifications::find($id);
        if ($checkNotification == null || ($urlValidation != $id)) {
            return view('errors.404');
        }


        $authUserId = Auth::user()->id;

        $comment->user_id = Auth::user()->id;
        $comment->content = $request->content;
        $comment->notification_id = $id;
        $comment->save();

        $notificationsChangesDisplayedFlags = NotificationChangesDisplayedFlags::where('notification_id',$checkNotification->id)->first();
        if(!empty($notificationsChangesDisplayedFlags)){
            if($authUserId == $checkNotification->user_id){
                $notificationsChangesDisplayedFlags->comment_added_by_reporter_displayed = false;
            }elseif($authUserId == $checkNotification->displayed_by
                || ($authUserId != $checkNotification->user_id && $authUserId != $checkNotification->displayed_by)){
                $notificationsChangesDisplayedFlags->comment_added_by_realizator_displayed = false;
            }else{
                Session::flash('message_error', "Coś poszło nie tak");
            }
            $notificationsChangesDisplayedFlags->save();
        }

        Session::flash('message_ok', "Komentarz dodany pomyślnie!");
        return Redirect::back();
    }

    public function getNotficationsJanky(Request $request) {
        if($request->ajax()) {
            $yesterday = date("Y-m-d") . ' 00:00:00';
            $today = date('Y-m-d') . ' 23:00:00';

            $data = DB::table('dkj')
              ->select(DB::raw('
                  count(*) as sum_janky
              '))
              ->whereBetween('add_date', [$yesterday, $today])
              ->whereNull('manager_status')
              ->where('dkj_status', '=', 1)
              ->where('deleted', '=', 0)
              ->where('department_info_id', '=', Auth::user()->department_info_id)
              ->get();

            return $data;
        }
    }

    public function myNotifications() {
        $notifications = Notifications::where('displayed_by', '=', Auth::user()->id)->count();
        $unratedNotifications = Notifications::select('status','nr.id')
            ->leftJoin('notification_rating as nr','nr.notification_id','=','notifications.id')
            ->where('notifications.user_id','=',Auth::user()->id)
            ->where('status','=',3)
            ->whereNull('nr.id')
            ->count();
        $notRepairedNotifications = Notifications::where('status','=',2)->where('displayed_by',Auth::user()->id)->count();
        return view('admin.myNotifications')
            ->with('notifications', $notifications)
            ->with('unratedNotifications', $unratedNotifications)
            ->with('notRepairedNotifications', $notRepairedNotifications);
    }

    public function rateNotificationGet($id){
        $notification = Notifications::find($id);

        $notificationRating = NotificationRating::where('notification_id', $id)->with('rating_component')->first();
        /*if($notification == null || $notification->user_id != Auth::user()->id || $notification->displayed_by == null) {
            return view('errors.404');
        }*/

        $notificationRatingCriterion = NotificationRatingCriterion::with('rating_system');
        if ($notificationRating == null) {
            $notificationRatingCriterion->where('status',1);
        }
        $notificationRatingCriterion = $notificationRatingCriterion->get();

        return view('admin.rateNotification')
            ->with('notificationRatingCriterion', $notificationRatingCriterion)
            ->with('notificationRating', $notificationRating)
            ->with('notification', $notification);

    }

    public function rateNotificationPost(Request $request){
        if($request->response == 'success'){
            Session::flash('message_ok','Twoja opinia została przesłana');
            return Redirect::to('my_notifications');
        }else{
            return view('errors.404');
        }
    }

    public function rateNotificationAjax(Request $request){
        /**
         * Sprawdzenie czy powiadomienie nie zostało już ocenione
         */
        $notificationRating = NotificationRating::where('notification_id', $request->notificationId)->count();
        if ($notificationRating > 0) {
            return 'error.404';
        }

        /**
         * Sprawdzenie czy ID powiadomienia jest zgodne z formularzem
         */
        $id = $request->notificationId;
        $url_array = explode('/',URL::previous());
        $urlValidation = end($url_array);
        $checkNotification = Notifications::find($id);
        if ($checkNotification == null || ($urlValidation != $id)) {
            return 'error.404';
        }

        $notificationRatingCriterion = NotificationRatingCriterion::whereIn('id',collect($request->ratingsArray)->pluck('criterionId')->toArray())
            ->with('rating_system')->get();

        /**
         * Sprawdzenie czy przekazane wartości z formularza są prawidłowe
         */

        $validation = true;
        foreach ($request->ratingsArray as $ratingItem) {
            $itemNotificationRatingCriterion = $notificationRatingCriterion->where('id',$ratingItem['criterionId'])->first();
            if($itemNotificationRatingCriterion->rating_system->rating_start > intval($ratingItem['rating'])
                || $itemNotificationRatingCriterion->rating_system->rating_stop < intval($ratingItem['rating'])){
                $validation = false;
            }
        }
        if (!$validation) {
            return 'error.404';
        }

        /**
         * Sprawdzenie czy powiadomienie istnieje, jest danego uzytkowinika oraz czy zostało zakończone
         */
        $notification = Notifications::find($request->notificationId);
        if ($notification == null || $notification->user_id != Auth::user()->id || $notification->displayed_by == null) {
            return 'error.404';
        }

        $notificationRating = new NotificationRating();
        $notificationRating->notification_id = $notification->id;
        $notificationRating->comment = $request->comment;;
        $notificationRating->created_at = date("Y-m-d H:i:s");
        $notificationRating->updated_at = date("Y-m-d H:i:s");
        $notificationRating->save();


        $average_rating = 0;
        foreach ($request->ratingsArray as $ratingItem) {
            $itemNotificationRatingCriterion = $notificationRatingCriterion->where('id',$ratingItem['criterionId'])->first();
            $notificationRatingComponent = new NotificationRatingComponents();
            $notificationRatingComponent->notification_rating_id = $notificationRating->id;
            $notificationRatingComponent->notification_rating_criterion_id = intval($ratingItem['criterionId']);
            $notificationRatingComponent->rating = intval($ratingItem['rating']);
            $average_rating += Normalizer::normalize($notificationRatingComponent->rating,
                [$itemNotificationRatingCriterion->rating_system->rating_start, $itemNotificationRatingCriterion->rating_system->rating_stop]);
            $notificationRatingComponent->save();
        }
        $notificationRating->average_rating = round($average_rating/count($request->ratingsArray),2);
        $notificationRating->save();

        new ActivityRecorder(array_merge(['T'=>'Dodanie oceny zgłoszenia'],$notificationRating->toArray()),76,1);
        return 'success';
    }

    public function datatableMyNotifications(Request $request) {
        $data = DB::table('notifications')
            ->select(DB::raw('
                notifications.*,
                users.first_name as first_name,
                users.last_name as last_name,
                nr.id as notification_rating
            '))
            ->leftJoin('users', 'users.id', '=', 'notifications.displayed_by')
            ->leftJoin('notification_rating as nr','nr.notification_id','=','notifications.id')
            ->where('status','!=',0)
            ->where('notifications.user_id', '=', Auth::user()->id)
            ->get();

        return datatables($data)->make(true);
    }

    public function datatableMyHandledNotifications(Request $request) {
        $data = DB::table('notifications')
            ->select(DB::raw('
                notifications.*,
                concat(users.first_name," ",users.last_name ) as user_name,
                nr.id as nr_id,
                nr.comment,
                nr.average_rating
            '))
            ->leftJoin('users', 'users.id', '=', 'notifications.user_id')
            ->leftJoin('notification_rating as nr', 'nr.notification_id', '=', 'notifications.id')
            ->where('status','!=',0)
            ->where('displayed_by', '=', Auth::user()->id)
            ->get();

        return datatables($data)->make(true);
    }

    public function ITWorkerGet($id){
      $checkUser = User::find($id);
      if ($checkUser == null || $checkUser->status_work == 0) {
          return view('errors.404');
      }
      $current_month = date('Y-m').'%';
      $data = DB::table('judge_results')
          ->select(DB::raw('
              first_name,
              last_name,
              count(*) as user_sum,
              SUM(CASE WHEN repaired = 1 THEN 1 ELSE 0 END) as user_sum_repaired,
              AVG(judge_quality) as user_quality,
              AVG(judge_contact) as user_contact,
              AVG(judge_time) as user_time,
              AVG(judge_sum) as user_judge_sum,
              SUM(CASE WHEN response_after = 1 THEN 1 ELSE 0 END) as response_after,
              AVG(TIME_TO_SEC(notifications.sec))/3600 as notifications_time_sum
          '))
          ->leftJoin('users', 'users.id', '=', 'judge_results.it_id')
          ->leftJoin('notifications', 'notifications.id', '=', 'judge_results.notification_id')
          ->where('judge_results.it_id', '=', $id)
          ->where('notifications.created_at','like',$current_month)
          ->get();


        $story_of_problem = DB::table('notifications')
            ->select(DB::raw('
                notifications.*,
                users.first_name as first_name,
                users.last_name as last_name,
                nr.average_rating,
                nr.id as nr_id,
                nr.comment
            '))
            ->leftJoin('users', 'users.id', '=', 'notifications.user_id')
            ->leftJoin('notification_rating as nr', 'nr.notification_id', '=', 'notifications.id')
            ->where('notifications.displayed_by', '=', $id)
            ->get();

        return view('notifications.it_worker')
            ->with('user_data',$checkUser)
            ->with('user_results', $data)
            ->with('story_of_problem',$story_of_problem);
    }

    public function viewNotification($id) {
        $notification = Notifications::find($id);

        if ($notification == null) {
            return view('errors.404');
        }
        $notificationChangesDisplayedFlags = NotificationChangesDisplayedFlags::where('notification_id','=',$notification->id)->first();
        if(!empty($notificationChangesDisplayedFlags)){
            $notificationChangesDisplayedFlags->comment_added_by_realizator_displayed = true;
            $notificationChangesDisplayedFlags->status_change_displayed = true;
            $notificationChangesDisplayedFlags->save();
        }

        $it_user = User::find($notification->displayed_by);

        return view('notifications.view_notification')
            ->with('it_user', $it_user)
            ->with('notification', $notification);
    }
    public function delete_notification (Request $request)
    {
        if($request->ajax())
        {
            $notification = Notifications::find($request->notification_id);

            if($notification->user_id == Auth::user()->id)
            {
                if($notification->status == 1 || Auth::user()->user_type_id === 3)
                {
                    $notification->status = 0;
                    $notification->updated_at = date('Y-m-d H:i:s');
                    $notification->remove_date = date('Y-m-d H:i:s');
                    $notification->removed_by_user_id = Auth::user()->id;
                    $notification->save();
                    new ActivityRecorder(array_merge(['T'=>'Usunięcie zgłoszonego problemu'], $notification->toArray()),35,3);
                    return 1;
                }else
                    return 0;
            }else{
                if(Auth::user()->user_type_id === 3){
                    $notification->status = 0;
                    $notification->updated_at = date('Y-m-d H:i:s');
                    $notification->remove_date = date('Y-m-d H:i:s');
                    $notification->removed_by_user_id = Auth::user()->id;
                    $notification->save();
                    new ActivityRecorder(array_merge(['T'=>'Usunięcie zgłoszonego problemu'], $notification->toArray()),35,3);
                    return 1;
                }
                return 2;
            }
        }
    }

    public function notificationRating(Request $request){
        if($request->ajax()) {
            $notificationRating = NotificationRating::find($request->notificationRatingId);
            $notificationRatingComponents = NotificationRatingComponents::where('notification_rating_id',$request->notificationRatingId)->get();
            $notificationRatingCriterion = NotificationRatingCriterion::whereIn('id',$notificationRatingComponents->pluck('notification_rating_criterion_id')->toArray())
                ->with('rating_system')->get();
            return [
                'notificationRating' => $notificationRating,
                'notificationRatingComponents' => $notificationRatingComponents,
                'notificationRatingCriterion' => $notificationRatingCriterion
            ];
        }
    }
}
