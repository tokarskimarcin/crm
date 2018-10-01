<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 14.09.18
 * Time: 08:26
 */

namespace App\Utilities\DataProcessing;

use App\Http\Controllers\Statistics\DepartmentsConfirmationStatisticsController;
use App\Utilities\Dates\SecondsToTime;
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
            $dateGroupSum->avgTimeOnRecord      = 0;
            $dateGroupSum->agreement            = 0;
            $dateGroupSum->agreementPct         = 0;
            $dateGroupSum->uncertain            = 0;
            $dateGroupSum->uncertainPct         = 0;
            $dateGroupSum->refusal              = 0;
            $dateGroupSum->refusalPct           = 0;
            $dateGroupTimeOnRecordSum      = 0;
            $dateGroupClosedRecordsSum     = 0;
            $dateGroupFrequencySum  = 0;
            $dateGroupPairsSum      = 0;

            $clientRouteInfo[$dateGroup] = $clientRouteInfoByDateGroup->groupBy($secondGrouping); //default grouping is coach_id
            foreach ($clientRouteInfo[$dateGroup] as $secondGroup => $clientRouteInfoByDateGroupByCoachId){
                $secondGroupSum = (object)[];
                $secondGroupSum->secondGroup          = $secondGroup;
                $secondGroupSum->shows                = 0;
                $secondGroupSum->provision            = 0;
                $secondGroupSum->successful           = 0;
                $secondGroupSum->neutral              = 0;
                $secondGroupSum->unsuccessful         = 0;
                $secondGroupSum->unsuccessfulBadly    = 0;
                $secondGroupSum->recordsCount         = 0;
                $secondGroupSum->avgTimeOnRecord      = 0;
                $secondGroupSum->agreement            = 0;
                $secondGroupSum->agreementPct         = 0;
                $secondGroupSum->uncertain            = 0;
                $secondGroupSum->uncertainPct         = 0;
                $secondGroupSum->refusal              = 0;
                $secondGroupSum->refusalPct           = 0;
                $secondGroupTimeOnRecordSum            = 0;
                $secondGroupClosedRecordsSum           = 0;
                $secondGroupFrequencySum  = 0;
                $secondGroupPairsSum      = 0;

                $clientRouteInfo[$dateGroup][$secondGroup] = $clientRouteInfoByDateGroupByCoachId->groupBy('confirmingUserName');
                foreach ($clientRouteInfo[$dateGroup][$secondGroup] as $consultantConfirmationData){
                    $consultantConfirmationStatistics               = (collect($consultantConfirmationData[0])->toArray());
                    unset($consultantConfirmationStatistics['frequency']);
                    unset($consultantConfirmationStatistics['pairs']);
                    unset($consultantConfirmationStatistics['actual_success']);
                    $consultantConfirmationStatistics               = (object)$consultantConfirmationStatistics;
                    $consultantConfirmationStatistics->shows        = count($consultantConfirmationData);
                    $consultantConfirmationStatistics->dateGroup    = $dateGroup;
                    $consultantConfirmationStatistics->secondGroup  = $secondGroup;

                    $consultantConfirmationStatistics->successful           = 0;
                    $consultantConfirmationStatistics->neutral              = 0;
                    $consultantConfirmationStatistics->unsuccessful         = 0;
                    $consultantConfirmationStatistics->unsuccessfulBadly    = 0;
                    $consultantConfirmationStatistics->recordsCount         = 0;
                    $consultantConfirmationStatistics->provision            = 0;
                    $consultantConfirmationStatistics->avgTimeOnRecord      = 0;
                    $consultantConfirmationStatistics->agreement            = 0;
                    $consultantConfirmationStatistics->agreementPct         = 0;
                    $consultantConfirmationStatistics->uncertain            = 0;
                    $consultantConfirmationStatistics->uncertainPct         = 0;
                    $consultantConfirmationStatistics->refusal              = 0;
                    $consultantConfirmationStatistics->refusalPct           = 0;
                    $timeOnRecordSum            = 0;
                    $closedRecordsSum           = 0;
                    $consultantFrequencySum     = 0;
                    $consultantPairsSum         = 0;

                    /**
                     * getting every client route info for specified trainer consultant in specified week
                     */
                    foreach ($consultantConfirmationData as $confirmationInfo){
                        //consultant sums
                        $consultantConfirmationStatistics->provision += ProvisionLevels::get('consultant', $confirmationInfo->frequency);
                        //sums for average
                        if($confirmationInfo->frequency > 19){
                            $consultantConfirmationStatistics->successful += 1;
                        }else if($confirmationInfo->frequency > 15){
                            $consultantConfirmationStatistics->neutral += 1;
                        }else if($confirmationInfo->frequency > 11){
                            $consultantConfirmationStatistics->unsuccessful += 1;
                        }else{
                            $consultantConfirmationStatistics->unsuccessfulBadly += 1;
                        }
                        $consultantConfirmationStatistics->recordsCount += $confirmationInfo->actual_success;
                        $consultantFrequencySum += $confirmationInfo->frequency;
                        $consultantPairsSum  += $confirmationInfo->pairs;
                    }
                    //counting percentages for consultant
                    $consultantConfirmationStatistics->successfulPct        = round($consultantConfirmationStatistics->successful*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->neutralPct           = round($consultantConfirmationStatistics->neutral*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->unsuccessfulPct      = round($consultantConfirmationStatistics->unsuccessful*100/$consultantConfirmationStatistics->shows, 2);
                    $consultantConfirmationStatistics->unsuccessfulBadlyPct = round($consultantConfirmationStatistics->unsuccessfulBadly*100/$consultantConfirmationStatistics->shows, 2);
                    //counting averages for consultant
                    $consultantConfirmationStatistics->avgFrequency     = round($consultantFrequencySum/$consultantConfirmationStatistics->shows,2);
                    $consultantConfirmationStatistics->avgPairs         = round($consultantPairsSum/$consultantConfirmationStatistics->shows,2);

                    $consultantConfirmationReports = DepartmentsConfirmationStatisticsController::getEveryPbxConfirmationReport($consultantConfirmationStatistics->confirmingUser, $dateGroupSum);

                    foreach($consultantConfirmationReports as $confirmationReport){
                        if(!is_null($confirmationReport['time_on_record'])){
                            $timeOnRecord = explode(":",$confirmationReport['time_on_record']);
                            $timeOnRecordSum += ($timeOnRecord[0]*3600 + $timeOnRecord[1]*60 + $timeOnRecord[2])*$confirmationReport['records_closed'];
                            $closedRecordsSum += $confirmationReport['records_closed'];
                        }
                        $consultantConfirmationStatistics->agreement += $confirmationReport['records_agreement'];
                        $consultantConfirmationStatistics->uncertain += $confirmationReport['records_uncertain'];
                        $consultantConfirmationStatistics->refusal += $confirmationReport['records_refusal'];
                    }
                    $consultantConfirmationStatistics->avgTimeOnRecord      = SecondsToTime::get($closedRecordsSum > 0 ? round($timeOnRecordSum/$closedRecordsSum,0) : 0);

                    $consultantConfirmationStatistics->agreementPct         = round($consultantConfirmationStatistics->agreement*100/$consultantConfirmationStatistics->recordsCount, 2);
                    $consultantConfirmationStatistics->uncertainPct         = round($consultantConfirmationStatistics->uncertain*100/$consultantConfirmationStatistics->recordsCount, 2);
                    $consultantConfirmationStatistics->refusalPct           = round($consultantConfirmationStatistics->refusal*100/$consultantConfirmationStatistics->recordsCount, 2);

                    //adding consultant to data
                    $confirmationStatistics['data']->push($consultantConfirmationStatistics);

                    //second group sums
                    $secondGroupSum->shows                  += $consultantConfirmationStatistics->shows;
                    $secondGroupSum->provision              += $consultantConfirmationStatistics->provision;
                    $secondGroupSum->successful             += $consultantConfirmationStatistics->successful;
                    $secondGroupSum->neutral                += $consultantConfirmationStatistics->neutral;
                    $secondGroupSum->unsuccessful           += $consultantConfirmationStatistics->unsuccessful;
                    $secondGroupSum->unsuccessfulBadly      += $consultantConfirmationStatistics->unsuccessfulBadly;
                    $secondGroupSum->recordsCount           += $consultantConfirmationStatistics->recordsCount;

                    $secondGroupSum->agreement              += $consultantConfirmationStatistics->agreement;
                    $secondGroupSum->uncertain              += $consultantConfirmationStatistics->uncertain;
                    $secondGroupSum->refusal                += $consultantConfirmationStatistics->refusal;


                    //second group sums for average
                    $secondGroupTimeOnRecordSum     += $timeOnRecordSum;
                    $secondGroupClosedRecordsSum    += $closedRecordsSum;
                    $secondGroupFrequencySum      += $consultantFrequencySum;
                    $secondGroupPairsSum          += $consultantPairsSum;
                }
                //counting percentages for second group
                $secondGroupSum->successfulPct        = round($secondGroupSum->successful*100/$secondGroupSum->shows, 2);
                $secondGroupSum->neutralPct           = round($secondGroupSum->neutral*100/$secondGroupSum->shows, 2);
                $secondGroupSum->unsuccessfulPct      = round($secondGroupSum->unsuccessful*100/$secondGroupSum->shows, 2);
                $secondGroupSum->unsuccessfulBadlyPct = round($secondGroupSum->unsuccessfulBadly*100/$secondGroupSum->shows, 2);

                $secondGroupSum->agreementPct         = round($secondGroupSum->agreement*100/$secondGroupSum->recordsCount, 2);
                $secondGroupSum->uncertainPct         = round($secondGroupSum->uncertain*100/$secondGroupSum->recordsCount, 2);
                $secondGroupSum->refusalPct           = round($secondGroupSum->refusal*100/$secondGroupSum->recordsCount, 2);

                //counting averages for second group
                $secondGroupSum->avgTimeOnRecord      = SecondsToTime::get($secondGroupClosedRecordsSum > 0 ? round($secondGroupTimeOnRecordSum/$secondGroupClosedRecordsSum,0) : 0);
                $secondGroupSum->avgFrequency     = round($secondGroupFrequencySum/$secondGroupSum->shows,2);
                $secondGroupSum->avgPairs         = round($secondGroupPairsSum/$secondGroupSum->shows,2);

                //adding second group sum to week
                $dateGroupSum->secondGrouping->push($secondGroupSum);
                //date sums
                $dateGroupSum->shows                += $secondGroupSum->shows;
                $dateGroupSum->provision            += $secondGroupSum->provision;
                $dateGroupSum->successful           += $secondGroupSum->successful;
                $dateGroupSum->neutral              += $secondGroupSum->neutral;
                $dateGroupSum->unsuccessful         += $secondGroupSum->unsuccessful;
                $dateGroupSum->unsuccessfulBadly    += $secondGroupSum->unsuccessfulBadly;
                $dateGroupSum->recordsCount         += $secondGroupSum->recordsCount;

                $dateGroupSum->agreement              += $secondGroupSum->agreement;
                $dateGroupSum->uncertain              += $secondGroupSum->uncertain;
                $dateGroupSum->refusal                += $secondGroupSum->refusal;

                //date sums for average
                $dateGroupTimeOnRecordSum     += $secondGroupTimeOnRecordSum;
                $dateGroupClosedRecordsSum    += $secondGroupClosedRecordsSum;
                $dateGroupFrequencySum      = $dateGroupFrequencySum + $secondGroupFrequencySum;
                $dateGroupPairsSum          = $dateGroupPairsSum + $secondGroupPairsSum;
            }
            //counting percentages for date
            $dateGroupSum->successfulPct        = round($dateGroupSum->successful*100/$dateGroupSum->shows, 2);
            $dateGroupSum->neutralPct           = round($dateGroupSum->neutral*100/$dateGroupSum->shows, 2);
            $dateGroupSum->unsuccessfulPct      = round($dateGroupSum->unsuccessful*100/$dateGroupSum->shows, 2);
            $dateGroupSum->unsuccessfulBadlyPct = round($dateGroupSum->unsuccessfulBadly*100/$dateGroupSum->shows, 2);

            $dateGroupSum->agreementPct         = round($dateGroupSum->agreement*100/$dateGroupSum->recordsCount, 2);
            $dateGroupSum->uncertainPct         = round($dateGroupSum->uncertain*100/$dateGroupSum->recordsCount, 2);
            $dateGroupSum->refusalPct           = round($dateGroupSum->refusal*100/$dateGroupSum->recordsCount, 2);
            //counting averages for date
            $dateGroupSum->avgTimeOnRecord      = SecondsToTime::get($dateGroupClosedRecordsSum > 0 ? round($dateGroupTimeOnRecordSum/$dateGroupClosedRecordsSum,0) : 0);
            $dateGroupSum->avgFrequency     = round($dateGroupFrequencySum/$dateGroupSum->shows,2);
            $dateGroupSum->avgPairs         = round($dateGroupPairsSum/$dateGroupSum->shows,2);

            //adding period to sums
            $confirmationStatistics['sums']->push($dateGroupSum);
        }
        return $confirmationStatistics;
    }
}