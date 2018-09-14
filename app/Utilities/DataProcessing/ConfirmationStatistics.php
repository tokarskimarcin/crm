<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 14.09.18
 * Time: 08:26
 */

namespace App\Utilities\DataProcessing;

use App\Utilities\Salary\ProvisionLevels;

class ConfirmationStatistics
{
    /**
     * @param $clientRouteInfo - collection that need to have 'confirmDate', 'frequency', 'pairs', 'actual_success', 'users.first_name', 'users.last_name',
     * 'trainer.first_name as t_first_name', 'trainer.last_name as t_last_name', 'users.coach_id'
     * @param $dividedMonth - array that every cell has object with `firstDay` and `lastDay` fields
     * @return \Illuminate\Support\Collection
     */
    public static function getConsultantsConfirmationStatisticsCollectionForMonth($clientRouteInfo, $dividedMonth){
        $clientRouteInfo = $clientRouteInfo->sortBy('confirmDate');
        foreach($clientRouteInfo as $consultantConfirmation){
            foreach ($dividedMonth as $week){
                if(strtotime($consultantConfirmation->confirmDate) >= strtotime($week->firstDay)
                    && strtotime($consultantConfirmation->confirmDate) <= strtotime($week->lastDay)){
                    $consultantConfirmation->dateGroup =  date('Y.m.d',strtotime($week->firstDay)).' - '.date('Y.m.d',strtotime($week->lastDay));
                    break;
                }
            }
        }
        $confirmationStatistics = collect();

        $clientRouteInfo = $clientRouteInfo->groupBy('dateGroup');
        foreach ($clientRouteInfo as $dateGroup => $clientRouteInfoByDateGroup){
            $clientRouteInfo[$dateGroup] = $clientRouteInfoByDateGroup->groupBy('coach_id');
            foreach ($clientRouteInfo[$dateGroup] as $coachId => $clientRouteInfoByDateGroupByCoachId){
                $clientRouteInfo[$dateGroup][$coachId] = $clientRouteInfoByDateGroupByCoachId->groupBy('confirmingUser');
                foreach ($clientRouteInfo[$dateGroup][$coachId] as $consultantConfirmationData){
                    $consultantConfirmationStatistics               = (object)[];
                    $consultantConfirmationStatistics->name         = $consultantConfirmationData[0]->first_name.' '.$consultantConfirmationData[0]->last_name;
                    $consultantConfirmationStatistics->trainer      = $consultantConfirmationData[0]->t_first_name.' '.$consultantConfirmationData[0]->t_last_name;
                    $consultantConfirmationStatistics->shows        = count($consultantConfirmationData);
                    $consultantConfirmationStatistics->dateGroup    = $dateGroup;

                    $consultantConfirmationStatistics->successful           = 0;
                    $consultantConfirmationStatistics->neutral              = 0;
                    $consultantConfirmationStatistics->unsuccessful         = 0;
                    $consultantConfirmationStatistics->unsuccessfulBadly    = 0;
                    $consultantConfirmationStatistics->recordsCount         = 0;
                    $consultantConfirmationStatistics->provision            = 0;
                    $frequencySum = 0;
                    $pairsSum = 0;

                    //getting every client route info for specified trainer consultant in specified week
                    foreach ($consultantConfirmationData as $confirmationInfo){
                        $frequencySum = $frequencySum + $confirmationInfo->frequency;
                        $consultantConfirmationStatistics->provision = $consultantConfirmationStatistics->provision + ProvisionLevels::get($confirmationInfo->frequency,'consultant');
                        $pairsSum = $pairsSum + $confirmationInfo->pairs;
                        if($confirmationInfo->frequency > 19){
                            $consultantConfirmationStatistics->successful = $consultantConfirmationStatistics->successful + 1;
                        }else if($confirmationInfo->frequency > 15){
                            $consultantConfirmationStatistics->neutral = $consultantConfirmationStatistics->neutral + 1;
                        }else if($confirmationInfo->frequency > 11){
                            $consultantConfirmationStatistics->unsuccessful = $consultantConfirmationStatistics->unsuccessful + 1;
                        }else{
                            $consultantConfirmationStatistics->unsuccessfulBadly = $consultantConfirmationStatistics->unsuccessfulBadly +1;
                        }
                        $consultantConfirmationStatistics->recordsCount = $consultantConfirmationStatistics->recordsCount + $confirmationInfo->actual_success;
                    }
                    $consultantConfirmationStatistics->successfulPct        = round($consultantConfirmationStatistics->successful*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->neutralPct           = round($consultantConfirmationStatistics->neutral*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->unsuccessfulPct      = round($consultantConfirmationStatistics->unsuccessful*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->unsuccessfulBadlyPct = round($consultantConfirmationStatistics->unsuccessfulBadly*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->avgFrequency         = round($frequencySum/$consultantConfirmationStatistics->shows,2);
                    $consultantConfirmationStatistics->avgPairs             = round($pairsSum/$consultantConfirmationStatistics->shows,2);
                    $confirmationStatistics->push($consultantConfirmationStatistics);
                }
            }
        }
        return $confirmationStatistics;
    }
}