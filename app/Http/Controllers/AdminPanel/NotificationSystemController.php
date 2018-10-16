<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 09.10.18
 * Time: 14:32
 */

namespace App\Http\Controllers\AdminPanel;

use App\JudgeResult;
use App\NotificationRating;
use App\NotificationRatingComponents;
use App\NotificationRatingCriterion;
use App\NotificationRatingSystem;
use App\Utilities\NumbersProcessing\Normalizer;
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

    public function ratingCriterionStatusChangeAjax(Request $request){
        if($request->ajax()){
            NotificationRatingCriterion::where('id',$request->ratingCriterionId)->update(['status'=>$request->status]);
            return 'success';
        }
        return false;
    }

    public function convertJRtoNewSystem(){
        $judgeResults = JudgeResult::all();
        foreach($judgeResults->sortBy('id') as $judgeResult){
            $notificationRating = new NotificationRating();
            $notificationRating->notification_id = $judgeResult->notification_id;
            $notificationRating->comment = $judgeResult->comment;
            $notificationRating->created_at = $judgeResult->created_at;
            $notificationRating->updated_at = $judgeResult->updated_at;
            $notificationRating->save();

            $average_rating = 0;

            $notificationRatingComponent = new NotificationRatingComponents();
            $notificationRatingComponent->notification_rating_id = $notificationRating->id;
            $notificationRatingComponent->notification_rating_criterion_id = 1; // "Czy problem został naprawiony?"
            $notificationRatingComponent->rating = $judgeResult->repaired == 1 ? 2 : 1;
            $average_rating+= Normalizer::normalize($notificationRatingComponent->rating, [1,2]);
            $notificationRatingComponent->save();


            $notificationRatingComponent = new NotificationRatingComponents();
            $notificationRatingComponent->notification_rating_id = $notificationRating->id;
            $notificationRatingComponent->notification_rating_criterion_id = 2; // "Oceń jakość wykonania zgłoszenia:"
            $notificationRatingComponent->rating = $judgeResult->judge_quality;
            $average_rating+= Normalizer::normalize($notificationRatingComponent->rating, [1,6]);
            $notificationRatingComponent->save();

            $notificationRatingComponent = new NotificationRatingComponents();
            $notificationRatingComponent->notification_rating_id = $notificationRating->id;
            $notificationRatingComponent->notification_rating_criterion_id = 3; // "Oceń kontakt z serwisantem:"
            $notificationRatingComponent->rating = $judgeResult->judge_contact;
            $average_rating+= Normalizer::normalize($notificationRatingComponent->rating, [1,6]);
            $notificationRatingComponent->save();

            $notificationRatingComponent = new NotificationRatingComponents();
            $notificationRatingComponent->notification_rating_id = $notificationRating->id;
            $notificationRatingComponent->notification_rating_criterion_id = 4; // "Oceń czas wykonywania zgłoszenia:"
            $notificationRatingComponent->rating = $judgeResult->judge_time;
            $average_rating+= Normalizer::normalize($notificationRatingComponent->rating, [1,6]);
            $notificationRatingComponent->save();

            $notificationRatingComponent = new NotificationRatingComponents();
            $notificationRatingComponent->notification_rating_id = $notificationRating->id;
            $notificationRatingComponent->notification_rating_criterion_id = 5; // "Czy technik kontaktował się po zakończeniu zgłoszenia?"
            $notificationRatingComponent->rating = $judgeResult->response_after == 1 ? 2 : 1;
            $average_rating+= Normalizer::normalize($notificationRatingComponent->rating, [1,2]);
            $notificationRatingComponent->save();

            $notificationRating->average_rating = round($average_rating/5,2);
            $notificationRating->save();
        }
        dd("Done");
    }
}