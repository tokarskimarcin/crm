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
use App\ActivityRecorder;
use App\AttemptResult;

class RecruitmentAttemptController extends Controller
{

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
     * Funkcja dodająca źródła kandydatów
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

            $data = [
                'Edycja źródła kandydata' => '',
                'Nowa nazwa' => $request->name,
                'Id źródła' => $candidate_source->id
            ];

            //new ActivityRecorder(8, $data);

            return 1;
        }
    }

    /**
     * Funkcja usuwająca/przywracająca źródła kandydatów
     */
    public function deleteCandidateSource(Request $request) {
        if ($request->ajax()) {
            $id = $request->id;

            $source = CandidateSource::find($id);

            if (!$source) {
                return 0;
            }

            $source->deleted = $request->deleted;
            $source->updated_at = date('Y-m-d H:i:s');
            $source->save();

            $data = [
                'Zmiana statusu źródła: 1 - wyłączone, 0 - włączone' => '',
                'Nowy status' => $request->deleted,
                'ID źródła' => $source->id
            ];

            //new ActivityRecorder(8, $data);

            return 1;
        }
    }

     /**
     * Widok z zarządzaniem etapami i źródłami
     */
    public function recruitment_resources() {
        $attempt_status = AttemptStatus::all();
        $attempt_results = AttemptResult::all();

        return view('recruitment.recruitmentResources')
            ->with('attempt_results', $attempt_results)
            ->with('attempt_status', $attempt_status);
    }

    /**
     * Funkcja zwracająca panel administracyjny dla rekrutera
     */
    public function interviewsAllGet() {
        $active_recruitments = Candidate::where('cadre_id', '=', Auth::user()->id)->whereNotIn('attempt_status_id', [10,11])->count();

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
                    candidate.last_name as user_surname,
                    candidate.id as candidate_id
                '))
                ->join('candidate', 'candidate.id', 'recruitment_attempt.candidate_id')
                ->where('candidate.attempt_status_id', '=', 3)
                ->where('interview_cadre', '=', Auth::user()->id)
                ->where('recruitment_attempt.status', '=', 0)
                ->whereBetween('recruitment_attempt.interview_date', [$request->start_search . ' 00:00:00', $request->stop_search . ' 23:00:00'])
                ->get();

            return $candidates;
        }
    }

   /**
    * Główne statystyki rekrutacji
    */
    public function recruitment_admin() {
//        $id = 1364;
//        $maxIds = DB::table('recruitment_story')
//            ->select(DB::raw('
//                MAX(recruitment_story.id) as id
//                '))
//            ->join('recruitment_attempt', 'recruitment_attempt.id', 'recruitment_story.recruitment_attempt_id')
//            ->where('recruitment_attempt.cadre_id', '=', $id)
//            ->where('recruitment_attempt.status', '=', 1)
//            ->groupBy('recruitment_attempt_id')
//            ->get();
//        $ids = [];
//        $ids[] = $maxIds->map(function($item) {
//            return $item->id;
//        });
//
//        $query = DB::table('recruitment_story')
//            ->select(DB::raw('
//                count(recruitment_story.id) as sum,
//                attempt_status.name as attempt_status_name,
//                attempt_result.name as attempt_result_name
//            '))
//            ->join('recruitment_attempt', 'recruitment_attempt.id', 'recruitment_story.recruitment_attempt_id')
//            ->leftJoin('attempt_result', 'attempt_result.id', 'recruitment_story.attempt_result_id')
//            ->leftJoin('attempt_status', 'attempt_status.id', 'recruitment_story.attempt_status_id')
//            ->whereIn('recruitment_story.id', $ids[0]->toArray())
//            ->groupBy('attempt_status.id', 'attempt_result.id')
//            ->get();
//dd($query);
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
        $recruitment_ok = Candidate::where('attempt_status_id', '=', 10)->count();

        /**
         * Dane rekruterów
         */
        $recruiters = User::whereIn('id', $cadre_array)->get();

        /**
         * Pobranie danych osób prowadzących szkolenia
         */
        $trainers = DB::table('group_training')
            ->select(DB::raw('
                COUNT(group_training.id) as trainer_sum,
                first_name,
                last_name,
                users.id,
                departments.name as dep_name,
                department_type.name as dep_type_name
            '))
            ->join('users', 'users.id', 'group_training.leader_id')
            ->join('department_info', 'department_info.id', 'users.department_info_id')
            ->join('departments', 'departments.id', 'department_info.id_dep')
            ->join('department_type', 'department_info.id_dep_type', 'department_type.id')
            ->groupBy('users.id')
            ->get();

        return view('recruitment.recruitmentStatistics')
            ->with('training_sum', $training_sum)
            ->with('trainers', $trainers)
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
             * Pobranie ilości udanych rekrutacji
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

            /**
             * Zliczenie ilośi etapów na jakiej kończy się rekrutację
             */
            $maxIds = DB::table('recruitment_story')
                ->select(DB::raw('
                MAX(recruitment_story.id) as id
                '))
                ->join('recruitment_attempt', 'recruitment_attempt.id', 'recruitment_story.recruitment_attempt_id')
                ->where('recruitment_attempt.cadre_id', '=', $id)
                ->where('recruitment_attempt.status', '=', 1)
                ->groupBy('recruitment_attempt_id')
                ->get();
            $ids = [];
            $ids[] = $maxIds->map(function($item) {
                return $item->id;
            });

            $query = DB::table('recruitment_story')
                ->select(DB::raw('
                count(recruitment_story.id) as sum,
                attempt_status.name as attempt_status_name,
                attempt_result.name as attempt_result_name
            '))
                ->join('recruitment_attempt', 'recruitment_attempt.id', 'recruitment_story.recruitment_attempt_id')
                ->leftJoin('attempt_result', 'attempt_result.id', 'recruitment_story.last_attempt_result_id')
                ->leftJoin('attempt_status', 'attempt_status.id', 'recruitment_story.last_attempt_status_id')
                ->whereIn('recruitment_story.id', $ids[0]->toArray())
                ->groupBy('attempt_status.id', 'attempt_result.id')
                ->get();

            $recruitement_sum_total = $query->sum('sum');

            /**
             * Pobranie źródeł rekrutacji dla danego rekrutera
             */
            $recruiter_sources = DB::table('candidate')
                ->select(DB::raw('
                    candidate_source.name,
                    count(candidate.id) as sum
                '))
                ->join('candidate_source', 'candidate_source.id', 'candidate.candidate_source_id')
                ->where('candidate.cadre_id', '=', $id)
                ->groupBy('candidate.candidate_source_id')
                ->get();

            /**
             * Zwrócenie potężnej ilości danych
             */
            $data = [
                'user'                  => $user,
                'recruitment_sum'       => $recruitment_sum,
                'all_sum'               => $all_sum,
                'interviews_sum'        => $interviews_sum,
                'training_data'         => $training_data,
                'recruitement_sum_total'=> $recruitement_sum_total,
                'recruiter_sources'     => $recruiter_sources,
                'query'                 => $query
            ];

            return $data;
        }
    }

    public function trainerData(Request $request) {
        if ($request->ajax()) {
            $id = $request->id;

            /**
             * Pobranie danych trenera
             */
            $user = User::find($id);

            if ($user == null) {
                return 0;
            }

            /**
             * Pobranie danych departamentu użytkownika
             */
            $user_department = '';
            $user_department .= $user->department_info->departments->name . ' ';
            $user_department .= $user->department_info->department_type->name;

            /**
             * Pobranie danych na temat wszystkich szkoleń przeprowadzonych przez trenera
             */
            $userTrainings = GroupTraining::where('leader_id', '=', $id)->orderBy('training_date', 'desc')->get();

            /**
             * Pobranie sumy osob na szkoleniu
             */
            $candidate_sum = $userTrainings->sum('candidate_count');

            /**
             * Pobranie sumy szkoleń dla danego trenera
             */
            $user_training_count = $userTrainings->count();

            /**
             * Zwrócenie mniej potężnej ilości danych
             */
            $data = [
                'user' => $user,
                'userTrainings' => $userTrainings,
                'user_department' => $user_department,
                'candidate_sum' => $candidate_sum,
                'user_training_count' => $user_training_count
            ];

            return $data;
        }
    }

    /**
     * Zarządzanie rezultatami etapów
     */
    public function getStatusResults(Request $request) {
        if ($request->ajax()) {
            $data = AttemptStatus::find($request->selected_status);
            $data = $data->attemptResult;
            return $data;
        }
    }

    /**
     * Dodawanie/usuwanie rezultatów etapów
     */
    public function statusResultChange(Request $request) {
        if ($request->ajax()) {
            if ($request->type == 'add') {
                DB::table('attempt_result_attempt_status')
                    ->insert([
                        'attempt_status_id' => $request->selected_status,
                        'attempt_result_id' => $request->selected_result
                    ]);
                return $request->type;
            } elseif ($request->type == 'delete') {
                DB::table('attempt_result_attempt_status')
                    ->where('attempt_status_id', '=', $request->selected_status)
                    ->where('attempt_result_id', '=', $request->selected_result)
                    ->delete();
                return $request->type;
            }
        }
    }

    /**
     * Wyświetlanie strony ze statystykami wszystkich pracowników HR
     */

    public function recruitment_statistics_leaderGET(){
        $hr = User::where('user_type_id', '=', 3)->get();
        $attemptStatus = AttemptStatus::all();
        $attemptResult = AttemptResult::all();

        return view('recruitment.recruitmentStatisticsLeader')
            ->with('attemptStatus', $attemptStatus)
            ->with('attemptResult', $attemptResult)
            ->with('hr', $hr);
    }

    /**
     * Przygotowanie danych do datatable (Statystyki HR)
     */

    public function datatableRecruitmentStatisticsLeader(Request $request){
        if($request->ajax()){

            $maxIds = DB::table('recruitment_story')
                ->select(DB::raw('
                    MAX(id) as id
                '))
                ->groupBy('recruitment_attempt_id')
                ->get();
            $ids = [];
            $ids[] = $maxIds->map(function($item) {
                return $item->id;
            });

            if ($request->attempt_by_status != 0) {
                if ($request->attempt_by_status == 1) {
                    $query = DB::table('recruitment_story')
                        ->select(DB::raw('
                            users.first_name as cadre_first_name,
                            users.last_name as cadre_last_name,
                            candidate.first_name as candidate_first_name,
                            candidate.last_name as candidate_last_name,
                            attempt_status.name as attempt_status_name,
                            attempt_result.name as attempt_result_name,
                            recruitment_attempt.created_at as created_at,
                            recruitment_story.comment as comment
                        '))
                        ->join('recruitment_attempt', 'recruitment_attempt.id', 'recruitment_story.recruitment_attempt_id')
                        ->join('users', 'users.id', 'recruitment_attempt.cadre_id')
                        ->join('candidate', 'candidate.id', 'recruitment_attempt.candidate_id')
                        ->leftJoin('attempt_result', 'attempt_result.id', 'recruitment_story.last_attempt_result_id')
                        ->leftJoin('attempt_status', 'attempt_status.id', 'recruitment_story.last_attempt_status_id')
                        ->whereIn('recruitment_story.id', $ids[0]->toArray())
                        ->where('recruitment_attempt.status', '=', 1)
                        ->whereBetween('recruitment_story.created_at', [$request->start_date . ' 01:00:00', $request->stop_date . ' 23:00:00']);
                } else if ($request->attempt_by_status == 2) {
                    $query = DB::table('recruitment_story')
                        ->select(DB::raw('
                            users.first_name as cadre_first_name,
                            users.last_name as cadre_last_name,
                            candidate.first_name as candidate_first_name,
                            candidate.last_name as candidate_last_name,
                            attempt_status.name as attempt_status_name,
                            attempt_result.name as attempt_result_name,
                            recruitment_attempt.created_at as created_at,
                            recruitment_story.comment as comment
                        '))
                        ->join('recruitment_attempt', 'recruitment_attempt.id', 'recruitment_story.recruitment_attempt_id')
                        ->join('users', 'users.id', 'recruitment_attempt.cadre_id')
                        ->join('candidate', 'candidate.id', 'recruitment_attempt.candidate_id')
                        ->leftJoin('attempt_result', 'attempt_result.id', 'recruitment_story.attempt_result_id')
                        ->leftJoin('attempt_status', 'attempt_status.id', 'recruitment_story.attempt_status_id')
                        ->whereIn('recruitment_story.id', $ids[0]->toArray())
                        ->where('recruitment_attempt.status', '=', 0)
                        ->whereBetween('recruitment_story.created_at', [$request->start_date . ' 01:00:00', $request->stop_date . ' 23:00:00']);
                }
            }

            if ($request->hr_user != 0) {
                $query->where('recruitment_attempt.cadre_id', '=', $request->hr_user);
            }

            if ($request->attempt_status != 0) {
                $query->where('recruitment_story.attempt_status_id', '=', $request->attempt_status);
            }

            if ($request->attempt_result != 0) {
                $query->where('recruitment_story.attempt_result_id', '=', $request->attempt_result);
            }

            return datatables($query)->make(true);
        }
    }
}
