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
     * @param $clientRouteInfo - collection that need to have 'confirmDate', 'frequency', 'pairs', 'actual_success', 'concat(users.first_name," ",users.last_name) as confirmingUserName',
     * 'concat(trainer.first_name," ",trainer.last_name) as confirmingUserTrainerName'
     * @param $dividedMonth - array that every cell has object with `firstDay` and `lastDay` fields
     * @param string $secondGrouping - pointing on column that should be grouped after weeks grouping
     * @return array
     */
    public static function getConsultantsConfirmationStatisticsForMonth($clientRouteInfo, $dividedMonth, $secondGrouping = 'confirmingUserTrainerName'){
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
        $confirmationStatistics = ['data'=>collect(),'sums'=>collect()];
        $clientRouteInfo = $clientRouteInfo->groupBy('dateGroup');
        foreach ($clientRouteInfo as $dateGroup => $clientRouteInfoByDateGroup){
            $dates = explode(' ',$dateGroup);
            $firstDay = explode('.',$dates[0]);
            $lastDay = explode('.',$dates[2]);
            $dateGroupSum = (object)[];
            $dateGroupSum->dateGroup            = $dateGroup;
            $dateGroupSum->firstDay             = $firstDay[0].'-'.$firstDay[1].'-'.$firstDay[2];
            $dateGroupSum->lastDay              = $lastDay[0].'-'.$lastDay[1].'-'.$lastDay[2];
            $dateGroupSum->secondGrouping       = collect();
            $dateGroupSum->shows                = 0;
            $dateGroupSum->provision            = 0;
            $dateGroupSum->successful           = 0;
            $dateGroupSum->neutral              = 0;
            $dateGroupSum->unsuccessful         = 0;
            $dateGroupSum->unsuccessfulBadly    = 0;
            $dateGroupSum->recordsCount         = 0;
            $dateGroupFrequencySum  = 0;
            $dateGroupPairsSum      = 0;

            $clientRouteInfo[$dateGroup] = $clientRouteInfoByDateGroup->groupBy($secondGrouping); //default grouping is coach_id
            foreach ($clientRouteInfo[$dateGroup] as $secondGroup => $clientRouteInfoByDateGroupByCoachId){
                $coachSum = (object)[];
                $coachSum->secondGroup          = $secondGroup;
                $coachSum->shows                = 0;
                $coachSum->provision            = 0;
                $coachSum->successful           = 0;
                $coachSum->neutral              = 0;
                $coachSum->unsuccessful         = 0;
                $coachSum->unsuccessfulBadly    = 0;
                $coachSum->recordsCount         = 0;
                $coachFrequencySum  = 0;
                $coachPairsSum      = 0;

                $clientRouteInfo[$dateGroup][$secondGroup] = $clientRouteInfoByDateGroupByCoachId->groupBy('confirmingUserName');
                foreach ($clientRouteInfo[$dateGroup][$secondGroup] as $consultantConfirmationData){
                    $consultantConfirmationStatistics               = (object)[];
                    $consultantConfirmationStatistics->name         = $consultantConfirmationData[0]->confirmingUserName;
                    $consultantConfirmationStatistics->trainer      = $consultantConfirmationData[0]->confirmingUserTrainerName;
                    $consultantConfirmationStatistics->shows        = count($consultantConfirmationData);
                    $consultantConfirmationStatistics->dateGroup    = $dateGroup;
                    $consultantConfirmationStatistics->secondGroup  = $secondGroup;

                    $consultantConfirmationStatistics->successful           = 0;
                    $consultantConfirmationStatistics->neutral              = 0;
                    $consultantConfirmationStatistics->unsuccessful         = 0;
                    $consultantConfirmationStatistics->unsuccessfulBadly    = 0;
                    $consultantConfirmationStatistics->recordsCount         = 0;
                    $consultantConfirmationStatistics->provision            = 0;
                    $consultantFrequencySum   = 0;
                    $consultantPairsSum       = 0;

                    //getting every client route info for specified trainer consultant in specified week
                    foreach ($consultantConfirmationData as $confirmationInfo){
                        //consultant sums
                        $consultantConfirmationStatistics->provision = $consultantConfirmationStatistics->provision + ProvisionLevels::get($confirmationInfo->frequency,'consultant');
                        //sums for average
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
                        $consultantFrequencySum   = $consultantFrequencySum + $confirmationInfo->frequency;
                        $consultantPairsSum       = $consultantPairsSum + $confirmationInfo->pairs;
                    }
                    //counting percentages for consultant
                    $consultantConfirmationStatistics->successfulPct        = round($consultantConfirmationStatistics->successful*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->neutralPct           = round($consultantConfirmationStatistics->neutral*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->unsuccessfulPct      = round($consultantConfirmationStatistics->unsuccessful*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->unsuccessfulBadlyPct = round($consultantConfirmationStatistics->unsuccessfulBadly*100/$consultantConfirmationStatistics->shows, 2);
                    //counting averages for consultant
                    $consultantConfirmationStatistics->avgFrequency     = round($consultantFrequencySum/$consultantConfirmationStatistics->shows,2);
                    $consultantConfirmationStatistics->avgPairs         = round($consultantPairsSum/$consultantConfirmationStatistics->shows,2);
                    //adding consultant to data
                    $confirmationStatistics['data']->push($consultantConfirmationStatistics);

                    //coach sums
                    $coachSum->shows                = $coachSum->shows + $consultantConfirmationStatistics->shows;
                    $coachSum->provision            = $coachSum->provision + $consultantConfirmationStatistics->provision;
                    $coachSum->successful           = $coachSum->successful + $consultantConfirmationStatistics->successful;
                    $coachSum->neutral              = $coachSum->neutral + $consultantConfirmationStatistics->neutral;
                    $coachSum->unsuccessful         = $coachSum->unsuccessful + $consultantConfirmationStatistics->unsuccessful;
                    $coachSum->unsuccessfulBadly    = $coachSum->unsuccessfulBadly + $consultantConfirmationStatistics->unsuccessfulBadly;
                    $coachSum->recordsCount         = $coachSum->recordsCount + $consultantConfirmationStatistics->recordsCount;
                    //coach sums for average
                    $coachFrequencySum      = $coachFrequencySum + $consultantFrequencySum;
                    $coachPairsSum          = $coachPairsSum + $consultantPairsSum;
                }
                //counting percentages for coach
                $coachSum->successfulPct        = round($coachSum->successful*100/$coachSum->shows, 2);
                $coachSum->neutralPct           = round($coachSum->neutral*100/$coachSum->shows, 2);
                $coachSum->unsuccessfulPct      = round($coachSum->unsuccessful*100/$coachSum->shows, 2);
                $coachSum->unsuccessfulBadlyPct = round($coachSum->unsuccessfulBadly*100/$coachSum->shows, 2);
                //counting averages for coach
                $coachSum->avgFrequency     = round($coachFrequencySum/$coachSum->shows,2);
                $coachSum->avgPairs         = round($coachPairsSum/$coachSum->shows,2);

                //adding trainer to week
                $dateGroupSum->secondGrouping->push($coachSum);
                //date sums
                $dateGroupSum->shows                = $dateGroupSum->shows + $coachSum->shows;
                $dateGroupSum->provision            = $dateGroupSum->provision + $coachSum->provision;
                $dateGroupSum->successful           = $dateGroupSum->successful + $coachSum->successful;
                $dateGroupSum->neutral              = $dateGroupSum->neutral + $coachSum->neutral;
                $dateGroupSum->unsuccessful         = $dateGroupSum->unsuccessful + $coachSum->unsuccessful;
                $dateGroupSum->unsuccessfulBadly    = $dateGroupSum->unsuccessfulBadly + $coachSum->unsuccessfulBadly;
                $dateGroupSum->recordsCount         = $dateGroupSum->recordsCount + $coachSum->recordsCount;
                //date sums for average
                $dateGroupFrequencySum      = $dateGroupFrequencySum + $coachFrequencySum;
                $dateGroupPairsSum          = $dateGroupPairsSum + $coachPairsSum;
            }
            //counting percentages for date
            $dateGroupSum->successfulPct        = round($dateGroupSum->successful*100/$dateGroupSum->shows, 2);
            $dateGroupSum->neutralPct           = round($dateGroupSum->neutral*100/$dateGroupSum->shows, 2);
            $dateGroupSum->unsuccessfulPct      = round($dateGroupSum->unsuccessful*100/$dateGroupSum->shows, 2);
            $dateGroupSum->unsuccessfulBadlyPct = round($dateGroupSum->unsuccessfulBadly*100/$dateGroupSum->shows, 2);
            //counting averages for date
            $dateGroupSum->avgFrequency     = round($dateGroupFrequencySum/$dateGroupSum->shows,2);
            $dateGroupSum->avgPairs         = round($dateGroupPairsSum/$dateGroupSum->shows,2);

            //adding week to sums
            $confirmationStatistics['sums']->push($dateGroupSum);
        }
        return $confirmationStatistics;
    }
}