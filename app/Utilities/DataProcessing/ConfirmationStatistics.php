<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 14.09.18
 * Time: 08:26
 */

namespace App\Utilities\DataProcessing;


use App\Utilities\Dates\MonthIntoCompanyWeeksDivision;
use App\Utilities\Dates\MonthPerWeekDivision;
use App\Utilities\Salary\ProvisionLevels;

class ConfirmationStatistics
{
    public static function getConsultantsConfirmationStatisticsCollectionForMonth($clientRouteInfo, $monthIntoCompanyWeeksDivision){
        $clientRouteInfo = $clientRouteInfo->sortBy('confirmDate');
        foreach($clientRouteInfo as $consultantConfirmation){/*
            $consultantConfirmation->name = $consultantConfirmation->first_name.' '.$consultantConfirmation->last_name;
            $consultantConfirmation->trainer = $consultantConfirmation->t_first_name.' '.$consultantConfirmation->t_last_name;*/
            $confirmingTime = strtotime($consultantConfirmation->confirmDate);
            foreach ($monthIntoCompanyWeeksDivision as $week){
                if(date('W', $confirmingTime) == $week->weekNumber){
                    $consultantConfirmation->dateGroup =  date('Y.m.d',strtotime($week->firstDay)).' - '.date('Y.m.d',strtotime($week->lastDay));
                    break;
                }
            }
        }
        $confirmationStatisctics = collect();

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
                    foreach ($consultantConfirmationData as $confirmationInfo){
                        $frequencySum = $frequencySum + $confirmationInfo->frequency;
                        $consultantConfirmationStatistics->provision = $consultantConfirmationStatistics->provision + ProvisionLevels::get($confirmationInfo->frequency,'consultant', ProvisionLevels::$SUBTYPES);
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
                    $confirmationStatisctics->push($consultantConfirmationStatistics);
                }
            }
        }
        dd($confirmationStatisctics);
    }
}