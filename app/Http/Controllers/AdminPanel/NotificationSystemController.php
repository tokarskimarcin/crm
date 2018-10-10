<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 09.10.18
 * Time: 14:32
 */

namespace App\Http\Controllers\AdminPanel;



use App\NotificationRating;
use App\NotificationRatingCriterion;
use App\NotificationRatingSystem;
use Illuminate\Http\Request;

class NotificationSystemController
{
    public function notificationSystemGet(){
        return view('admin.notificationSystem');
    }

    public function ratingCriterionDataAjax(Request $request){
        if($request->ajax()){
            return NotificationRatingCriterion::with('rating_system')->get();
        }
        return false;
    }

    public function ratingSystemDataAjax(Request $request){
        if($request->ajax()) {
            return NotificationRatingSystem::all();
        }
        return false;
    }

    public function newRatingCriterionDataAjax(Request $request){
        if($request->ajax()){
            $notificationRatingCriterion = new NotificationRatingCriterion();
            $notificationRatingCriterion->criterion = $request->criterion;
            $notificationRatingCriterion->notification_rating_system_id = $request->ratingSystemId;
            $notificationRatingCriterion->save();
            return 'success';
        }
        return false;
    }

    public function ratingCriterionStatusChangeAjax(Request $request){
        if($request->ajax()){
            NotificationRatingCriterion::where('id',$request->ratingCriterionId)->update(['status'=>$request->status]);
            return 'success';
        }
        return false;
    }
    public function newRatingSystemDataAjax(Request $request){
        if($request->ajax()){
            $notificationRatingSystem = new NotificationRatingSystem();
            $notificationRatingSystem->rating_start = $request->ratingStart;
            $notificationRatingSystem->rating_stop = $request->ratingStop;
            $notificationRatingSystem->description = $request->description;
            $notificationRatingSystem->save();
            return 'success';
        }
        return false;
    }
}