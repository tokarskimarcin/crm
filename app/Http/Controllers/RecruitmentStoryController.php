<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\RecruitmentAttempt;
use App\RecruitmentStory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecruitmentStoryController extends Controller
{




    /**
     *   Zwrócenie danych na temat ilości spływu rekrutacji
     */
    public function  pageReportRecruitmentFlowGet(){
        $date_start = date('Y-m-d');
        $date_stop = date('Y-m-d');
        $flow_count = RecruitmentStory::getReportFlowData($date_start,$date_stop);
        return view('recruitment.reportRecruitmentFlow')
            ->with('date_start', $date_start)
            ->with('date_stop', $date_stop)
            ->with('flow_count',$flow_count);
    }
    public function  pageReportRecruitmentFlowPost(Request $request){
        $flow_count = RecruitmentStory::getReportFlowData($request->date_start,$request->date_stop);
        return view('recruitment.reportRecruitmentFlow')
            ->with('date_start', $request->date_start)
            ->with('date_stop', $request->date_stop)
            ->with('flow_count',$flow_count);
    }



    /**
     * Zwrócenie danych na temat ilości nowych kont w godziniówce
     */
    public function pageReportNewAccountGet(){
        $date_start = date('Y-m-d');
        $date_stop = date('Y-m-d');
        $select_type = 0;

        $data = RecruitmentStory::getReportNewAccountData($date_start, $date_stop);
        return view('recruitment.reportRecruitmentNewAccount')
            ->with('date_start', $date_start)
            ->with('date_stop', $date_stop)
            ->with('select_type', $select_type)
            ->with('data', $data);
    }

    /**
     * Wyszukanie danych na temat ilości nowych kont w godziniówce
     */
    public function pageReportNewAccountPost(Request $request){
        $date_start = $request->date_start;
        $date_stop = $request->date_stop;
        $select_type = 0;

        $data = RecruitmentStory::getReportNewAccountData($date_start, $date_stop);
        //dd($data);

        return view('recruitment.reportRecruitmentNewAccount')
            ->with('date_start', $date_start)
            ->with('date_stop', $date_stop)
            ->with('select_type', $select_type)
            ->with('data', $data);
        return view('recruitment.reportRecruitmentNewAccount');
    }


    /**
     * Zwrócenie danych na temat ilości rozmów rekrutacyjnych
     */
    public function pageReportInterviewsGet() {
        $date_start = date('Y-m-d');
        $date_stop = date('Y-m-d');
        $select_type = 0;

        $data = RecruitmentStory::getReportInterviewsData($date_start, $date_stop, $select_type);

        return view('recruitment.reportRecruitmentInterviews')
            ->with('date_start', $date_start)
            ->with('date_stop', $date_stop)
            ->with('select_type', $select_type)
            ->with('data', $data);
    }

    /**
     * Wyszukiwanie danych na temta ilości rozmów rekrutacyjnych
     */
    public function pageReportInterviewsPost(Request $request) {
        $date_start = $request->date_start;
        $date_stop = $request->date_stop;
        $select_type = $request->select_type;

        $data = RecruitmentStory::getReportInterviewsData($date_start, $date_stop, $select_type);

        return view('recruitment.reportRecruitmentInterviews')
            ->with('date_start', $date_start)
            ->with('date_stop', $date_stop)
            ->with('select_type', $select_type)
            ->with('data', $data);
    }



    /**
     * Wyświetlenie danych na temat szkoleń
     */
    public function pageReportTrainingGet() {
        return view('recruitment.reportTraining');
    }

    /**
     * Dane dotyczące ilości szkoleń
     */
    public function datatableTrainingData(Request $request) {
        $data = RecruitmentStory::getReportTrainingData($request->date_start,$request->date_stop);
        return datatables($data)->make(true);
    }
}
