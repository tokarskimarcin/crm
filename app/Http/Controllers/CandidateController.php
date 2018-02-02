<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\AttemptStatus;
use App\CandidateSource;
use App\Department_info;
use App\Candidate;
use App\RecruitmentAttempt;
use App\RecruitmentStory;

class CandidateController extends Controller
{
    public function all_candidates() {
        return view('recruitment.allCandidates');
    }

    public function datatableShowCandidates(Request $request) {
        $data = DB::table('candidate')
            ->select(DB::raw('
                candidate.*,
                users.first_name as cadre_name,
                users.last_name as cadre_surname
            '))
            ->join('users', 'users.id', 'candidate.cadre_id')
            ->orderBy('candidate.last_name')
            ->get();

        return datatables($data)->make(true);
    }

    /**
     * Funkcja zwracająca widok z templatką dodającą kandydata
     */
    public function add_candidate() {
        $department_info = Department_info::where('id', '!=', 13)->get();
        $sources = CandidateSource::all();
        $status = AttemptStatus::all();

        return view('recruitment.newCandidate')
            ->with('sources', $sources)
            ->with('status', $status)
            ->with('department_info', $department_info);
    }

    /**
     * Dodanie nowego kandydata
     */
    public function addNewCandidate(Request $request) {
        if ($request->ajax()) {
            $candidate = new Candidate();

            $candidate->first_name = $request->candidate_name;
            $candidate->last_name = $request->candidate_surname;
            $candidate->phone = $request->candidate_phone;
            $candidate->department_info_id = $request->candidate_department;
            $candidate->candidate_source_id = $request->candidate_source;
            $candidate->comment = $request->candidate_desc;
            $candidate->cadre_id = Auth::user()->id;
            $candidate->cadre_edit_id = Auth::user()->id;
            $candidate->attempt_status_id = 1;
            $candidate->created_at = date('Y-m-d H:i:s');
            $candidate->updated_at = date('Y-m-d H:i:s');

            $candidate->save();

            return $candidate->id;
        }
    }

    /**
     * Funkcja zwracająca widok z profilem kandydata
     */
    public function candidateProfile($id) {
        $candidate = Candidate::find($id);

        $candidate_status = ($candidate->attempt_level_data != null) ? $candidate->attempt_level_data->name : 'Brak aktywnej rekrutacji';

        if ($candidate == null) {
            return view('errors.404');
        }

        $department_info = Department_info::where('id', '!=', 13)->get();
        $sources = CandidateSource::all();
        $status = AttemptStatus::all();

        return view('recruitment.candidateProfile')
            ->with('sources', $sources)
            ->with('status', $status)
            ->with('candidate_status', $candidate_status)
            ->with('department_info', $department_info)
            ->with('candidate', $candidate);
    }

    /**
     * Edycja danych kandydata (nie jego etapow rekrutacji)
     */
    public function editCandidate(Request $request) {
        if ($request->ajax()) {
            $candidate = Candidate::find($request->candidate_id);

            if ($candidate == null) {
                return view('errors.404');
            }

            $candidate->first_name = $request->candidate_name;
            $candidate->last_name = $request->candidate_surname;
            $candidate->phone = $request->candidate_phone;
            $candidate->department_info_id = $request->candidate_department;
            $candidate->candidate_source_id = $request->candidate_source;
            $candidate->comment = $request->candidate_desc;
            $candidate->cadre_edit_id = Auth::user()->id;
            $candidate->updated_at = date('Y-m-d H:i:s');

            $candidate->save();

            return 1;
        }
    }

    /**
     * Dodanie etapu rekrutacji 
     */
    public function addStory($candidate_id, $attempt_id, $status, $comment) {
        $newStory = new RecruitmentStory();

        $newStory->candidate_id = $candidate_id;
        $newStory->cadre_id = Auth::user()->id;
        $newStory->cadre_edit_id = Auth::user()->id;
        $newStory->recruitment_attempt_id = $attempt_id;
        $newStory->attempt_status_id = $status;
        $newStory->comment = $comment;
        $newStory->created_at = date('Y-m-d H:i:s');
        $newStory->updated_at = date('Y-m-d H:i:s');

        $newStory->save();

        /**
         * Zaktualizowanie etapu rekrutacji w danych kandydata
         */
        $candidate_update = Candidate::find($candidate_id);

        $candidate_update->attempt_status_id = $status;
        $candidate_update->save();
    }

    /**
     * Rozpoczęcie nowej rekrutacji (dla istniejącego kandydata)
     */
    public function startNewRecruitment(Request $request) {
        if ($request->ajax()) {
            $id = $request->candidate_id;

            /**
             * Sprawdzenie czy kandydat nie ma już aktywnej rekrutacji
             */
            $recruitment_check = RecruitmentAttempt::where('candidate_id', '=', $id)->where('status', '=', 0)->count();

            if ($recruitment_check > 0) {
                return 2;
            }

            /**
             * Stworznie nowej rekrutacji
             */
            $newAttempt = new RecruitmentAttempt();

            $newAttempt->candidate_id = $id;
            $newAttempt->status = 0;
            $newAttempt->cadre_id = Auth::user()->id;
            $newAttempt->created_at = date('Y-m-d H:i:s');
            $newAttempt->updated_at = date('Y-m-d H:i:s');
            
            $newAttempt->save();

            /**
             * Dodanie pierwszego atepu w tej rekrutacji
             */
            $this->addStory($id, $newAttempt->id, $request->new_recruitment_status, $request->new_recruitment_comment);

            return 1;
        }
    }

    /**
     * Funkcja dezaktywująca rekrutację 
     * w zależności od flagi:
     *  0 - zakończenie rekrutacji bez dodawania kandydata jako konsultant
     *  1 - zakońcenie rekrutacji + dodanie nowego konsultanta
     */
    public function stopRecruitment(Request $request) {
        if ($request->ajax()) {
            $id = $request->candidate_id;
            $recruitmentAttempt = RecruitmentAttempt::where('candidate_id', '=', $id)->where('status', '=', 0)->first();

            if ($recruitmentAttempt == null) {
                return 0;
            }

            $recruitmentAttempt->status = 1;
            $recruitmentAttempt->cadre_edit_id = Auth::user()->id;
            $recruitmentAttempt->updated_at = date('Y-m-d H:i:s');

            $recruitmentAttempt->save();

            /**
             * Dodanie etapu w tej rekrutacji
             */
            $this->addStory($id, $recruitmentAttempt->id, $request->stop_recruitment_status, $request->stop_recruitment_comment);

            return 1;
        }
    }

    /**
     * Dodanie etapu rekrutacji
     */
    public function addRecruitmentLevel(Request $request) {
        if ($request->ajax()) {
            $id = $request->candidate_id;

            $recruitmentAttempt = RecruitmentAttempt::where('candidate_id', '=', $id)->where('status', '=', 0)->first();

            if ($recruitmentAttempt == null) {
                return 0;
            }

            /**
             * Dodanie etapu w tej rekrutacji
             */
            $this->addStory($id, $recruitmentAttempt->id, $request->add_level_status, $request->add_level_comment);

            if ($request->add_level_status == 3) {
                $date_time = $request->interview;
                $this->addInterviewDate($recruitmentAttempt->id, $date_time);
            }

            return 1;
        }
    }

    /**
     * Dodanie kandydata do treningu
     */
    public function addToTraining(Request $request) {
        if ($request->ajax()) {
            $id = $request->candidate_id;

            $recruitmentAttempt = RecruitmentAttempt::where('candidate_id', '=', $id)->where('status', '=', 0)->first();

            if ($recruitmentAttempt == null) {
                return 0;
            }

            /**
             * Dodanie etapu w tej rekrutacji
             */
            $this->addStory($id, $recruitmentAttempt->id, $request->add_level_status, $request->add_training_comment);

            return 1;
        }
    }

    /**
     * Sprawdzenie czy numer jest unikalny
     */
    public function uniqueCandidatePhone(Request $request) {
        if ($request->ajax()) {
            $candidate = Candidate::where('phone', '=', $request->candidate_phone)->count();

            return ($candidate > 0) ? 0 : 1 ;
        }
    }

     /**
     * Dodanie czasu rozmowy kwalifikacyjnej
     */
    public function addInterviewDate($recruitment_attempt_id, $recruitment_date) {
        $recruitment = RecruitmentAttempt::find($recruitment_attempt_id);
        $recruitment->interview_date = $recruitment_date;
        $recruitment->interview_cadre = Auth::user()->id;
        $recruitment->save();
    }
}
