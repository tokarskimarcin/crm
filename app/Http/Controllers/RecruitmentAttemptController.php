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
use App\User;

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
     * Funkcja zwracająca panel administracyjny dla rekrutera
     */
    public function interviewsAllGet() {
        $active_recruitments = Candidate::where('cadre_id', '=', Auth::user()->id)->whereNotIn('attempt_status_id', [10])->count();

        $today_interviews = DB::table('recruitment_attempt')
                ->select(DB::raw('
                    COUNT(candidate.id) as sum
                '))
                ->join('candidate', 'candidate.id', 'recruitment_attempt.candidate_id')
                ->where('candidate.attempt_status_id', '=', 3)
                ->where('interview_cadre', '=', Auth::user()->id)
                ->where('recruitment_attempt.interview_date', 'like', date('Y-m-d') . '%')
                ->get();

        $sum_interviews = Candidate::where('cadre_id', '=', Auth::user()->id)->count();

        $total_trainings = GroupTraining::where('cadre_id', '=', Auth::user()->id)->count();

        $incoming_trening = GroupTraining::where('leader_id', '=', Auth::user()->id)->where('status', '!=', 0)->get();

        return view('recruitment.interviewsAll')
            ->with('sum_interviews', $sum_interviews)
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

   /**
    * Główne statystyki rekrutacji
    */
    public function recruitment_admin() { $id =4796;
        
        /**
         * Ilość aktywnych rekrutacji
         */
        $active_recruitments = Candidate::whereNotIn('attempt_status_id', [10,11])->count();
        
        /**
         * Ilość dodanych szkoleń
         */
        $training_sum = GroupTraining::count();

        /**
         * Ilość rekruterów
         */
        $recruiter_sum = DB::table('candidate')
            ->select(DB::raw('
                DISTINCT(cadre_id) as cadre
            '))
            ->get();

        $cadre_array = [];

        foreach($recruiter_sum as $item) {
            $cadre_array[] = $item->cadre;
        }

        /**
         * Liczba pozytywnych rekrutacji
         */
        $recruitment_ok = Candidate::where('attempt_status_id', '=', 11)->count();

        /**
         * Dane rekruterów
         */
        $recruiters = User::whereIn('id', $cadre_array)->get();

        return view('recruitment.recruitmentStatistics')
            ->with('training_sum', $training_sum)
            ->with('recruiters', $recruiters)
            ->with('recruitment_ok', $recruitment_ok)
            ->with('recruiter_sum', count($cadre_array))
            ->with('active_recruitments', $active_recruitments);
    }

    /**
     * Dane dotyczące pojedyńczego rekrutera
     */
    public function recruiterData(Request $request) {
        if ($request->ajax()) {
            $id = $request->id;

            $user = User::find($id);

            /**
             * Sprawdzenie czy użytkownik istenieje
             */
            if (!$user) {
                return 0;
            }

            /**
             * Pobranie ilości idanych rekrutacji
             */
            $recruitment_sum = $user->userCandidates->where('attempt_status_id', '=', 10)->count();

            /**
             * Pobranie ilości szkoleń
             */
            $all_sum = $user->userCandidates->count();

            /**
             * Pobranie ilości kandydatów umówionych na rozmowę kwalifikacyjną
             */
            $interviews_sum = $user->userCandidates->where('attempt_status_id', '=', 3)->count();

            /**
             * Pobranie danych dotyczących szkoleń prowadzonych przez rekrutera 
             */
            //$training_data = $user->userTrainings;

            $training_data = DB::table('group_training')
                ->select(DB::raw('
                    group_training.*,
                    count(candidate_training.candidate_id) as candidate_sum,
                    SUM(CASE WHEN completed_training is null THEN 1 ELSE 0 END) as not_judged,
                    SUM(CASE WHEN recruitment_story.attempt_status_id = 8 THEN 1 ELSE 0 END) as candidate_pass,
                    SUM(CASE WHEN recruitment_story.attempt_status_id = 9 THEN 1 ELSE 0 END) as candidate_not_pass
                '))
                ->leftJoin('candidate_training', 'candidate_training.training_id', 'group_training.id')
                ->leftJoin('recruitment_story', 'recruitment_story.id', 'candidate_training.completed_training')
                ->where('leader_id', '=', $id)
                ->groupBy('group_training.id')
                ->get();

            $data = [
                'user'              => $user,
                'recruitment_sum'   => $recruitment_sum,
                'all_sum'           => $all_sum,
                'interviews_sum'    => $interviews_sum,
                'training_data'     => $training_data
            ];

            return $data;
        }
    }
}
