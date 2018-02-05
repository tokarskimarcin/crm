<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AttemptStatus;
use App\CandidateSource;
use App\Department_info;
use App\Candidate;
use App\RecruitmentAttempt;
use App\GroupTraining;
use App\RecruitmentStory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecruitmentAttemptController extends Controller
{

    /**
     * Funkcja zwracająca wszystkie etapy rekrutacji
     */
    public function getAttemptLevel(Request $request) {
        $data = AttemptStatus::where('status', '=', 0)->get();

        return $data;
    }

    /**
     * Funkcja dodająca kolejny etap rekrutacji
     */
    public function addAttemptLevel(Request $request) {
        if ($request->ajax()) {
            $attempt_status = new AttemptStatus();

            $attempt_status->name = $request->name;
            $attempt_status->status = 0;
            $attempt_status->created_at = date('Y-m-d H:i:s');
            $attempt_status->updated_at = date('Y-m-d H:i:s');
            $attempt_status->save();
    
            return 1;
        }
    }

    /**
     * Funkcja edytująca etapy rekrutacji
     */
    public function editAttemptLevel(Request $request) {
        if ($request->ajax()) {
            $attempt_status = AttemptStatus::find($request->id);

            if ($attempt_status == null) {
                return 0;
            }

            $attempt_status->name = $request->name;
            $attempt_status->updated_at = date('Y-m-d H:i:s');
            $attempt_status->save();
    
            return 1;
        }
    }

    /**
     * Funkcja usuwająca etapy rekrutacji (zmiana statusu na 1)
     */
    public function deleteAttemptLevel(Request $request) {
        if ($request->ajax()) {
            $attempt_status = AttemptStatus::find($request->id);

            if ($attempt_status == null) {
                return 0;
            }

            $attempt_status->status = 1;
            $attempt_status->save();

            return 1;
        }
    }

    /**
     * Funkcja zwracająca wszystkie źródła kandydatów
     */
    public function getCandidateSource(Request $request) {
        if ($request->ajax()) {
            $candidate_source = CandidateSource::all();

            return $candidate_source;
        }
    }

    /**
     * Funkcja dodająca źródła kandydató
     */
    public function addCandidateSource(Request $request) {
        if ($request->ajax()) {
            $candidate_source = new CandidateSource();

            $candidate_source->name = $request->name;
            $candidate_source->created_at = date('Y-m-d H:i:s');
            $candidate_source->updated_at = date('Y-m-d H:i:s');
            $candidate_source->save();
    
            return 1;
        }
    }

    /**
     * Funkcja do edycji źródła kandydatów
     */
    public function editCandidateSource(Request $request) {
        if ($request->ajax()) {
            $candidate_source = CandidateSource::find($request->id);

            if ($candidate_source == null) {
                return 0;
            }

            $candidate_source->name = $request->name;     
            $candidate_source->updated_at = date('Y-m-d H:i:s');     
            $candidate_source->save();

            return 1;
        }
    }

    /**
     * TO DO
     * Funkcja usuwająca źródłą kandydatów (brak pola status w bazie dancyh)
     */
    public function deleteCandidateSource(Request $request) {
        if ($request->ajax()) {

        }
    }

     /**
     * Widok z zarządzaniem etapami i źródłami
     */
    public function recruitment_resources() {
        return view('recruitment.recruitmentResources');
    }

    /**
     * Funkcja zwracająca wszystkie rozmowy kwalifikacyjne
     */
    public function interviewsAllGet() {
        $active_recruitments = Candidate::where('cadre_id', '=', Auth::user()->id)->whereNotIn('attempt_status_id', [10])->count();

        $today_interviews = DB::table('recruitment_attempt')
                ->select(DB::raw('
                    SUM(candidate.id) as sum
                '))
                ->join('candidate', 'candidate.id', 'recruitment_attempt.candidate_id')
                ->where('candidate.attempt_status_id', '=', 3)
                ->where('recruitment_attempt.status', '=', 0)
                ->where('interview_cadre', '=', Auth::user()->id)
                ->where('recruitment_attempt.interview_date', 'like', date('Y-m-d') . '%')
                ->get();

        $total_trainings = GroupTraining::where('cadre_id', '=', Auth::user()->id)->count();

        $incoming_trening = GroupTraining::where('leader_id', '=', Auth::user()->id)->where('status', '!=', 0)->get();

        return view('recruitment.interviewsAll')
            ->with('incoming_trening', $incoming_trening)
            ->with('today_interviews', $today_interviews[0]->sum)
            ->with('total_trainings', $total_trainings)
            ->with('active_recruitments', $active_recruitments);
    }

    /**
     * Zwrócenie oczekujących rozmow kwalifikacyjnych
     */
    public function myInterviews(Request $request) {
        if ($request->ajax()) {
            $candidates = DB::table('recruitment_attempt')
                ->select(DB::raw('
                    recruitment_attempt.*,
                    candidate.first_name as user_name,
                    candidate.last_name as user_surname
                '))
                ->join('candidate', 'candidate.id', 'recruitment_attempt.candidate_id')
                ->where('candidate.attempt_status_id', '=', 3)
                ->where('interview_cadre', '=', Auth::user()->id)
                ->whereBetween('recruitment_attempt.interview_date', [$request->start_search . ' 00:00:00', $request->stop_search . ' 23:00:00'])
                ->get();

            return $candidates;
        }
    }

   
}
