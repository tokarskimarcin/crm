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
            return NotificationRatingCriterion::all();
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