<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AttemptStatus;
use App\CandidateSource;
use App\Department_info;
use App\Candidate;
use App\RecruitmentAttempt;
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
            $candidate->created_at = date('Y-m-d H:i:s');
            $candidate->updated_at = date('Y-m-d H:i:s');

            $candidate->save();

            return $candidate->id;

            return 1;
        }
    }

    /**
     * Funkcja zwracająca widok z profilem kandydata
     */
    public function candidateProfile($id) {
        $candidate = Candidate::find($id);

        if ($candidate == null) {
            return view('errors.404');
        }

        $department_info = Department_info::where('id', '!=', 13)->get();
        $sources = CandidateSource::all();
        $status = AttemptStatus::all();

        return view('recruitment.candidateProfile')
            ->with('sources', $sources)
            ->with('status', $status)
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
     * Widok z zarządzaniem etapami i źródłami
     */
    public function recruitment_resources() {
        return view('recruitment.recruitmentResources');
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
            $newStory = new RecruitmentStory();

            $newStory->candidate_id = $id;
            $newStory->cadre_id = Auth::user()->id;
            $newStory->cadre_edit_id = Auth::user()->id;
            $newStory->recruitment_attempt_id = $newAttempt->id;
            $newStory->attempt_status_id = $request->new_recruitment_status;
            $newStory->comment = $request->new_recruitment_comment;
            $newStory->created_at = date('Y-m-d H:i:s');
            $newStory->updated_at = date('Y-m-d H:i:s');

            $newStory->save();

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
            $newStory = new RecruitmentStory();

            $newStory->candidate_id = $id;
            $newStory->cadre_id = Auth::user()->id;
            $newStory->cadre_edit_id = Auth::user()->id;
            $newStory->recruitment_attempt_id = $recruitmentAttempt->id;
            $newStory->attempt_status_id = $request->stop_recruitment_status;
            $newStory->comment = $request->stop_recruitment_comment;
            $newStory->created_at = date('Y-m-d H:i:s');
            $newStory->updated_at = date('Y-m-d H:i:s');

            $newStory->save();

            return 1;
        }
    }

    public function addRecruitmentLevel(Request $request) {
        if ($request->ajax()) {
            $id = $request->id;

/**
             * Dodanie etapu w tej rekrutacji
             */
            $newStory = new RecruitmentStory();

            $newStory->candidate_id = $id;
            $newStory->cadre_id = Auth::user()->id;
            $newStory->cadre_edit_id = Auth::user()->id;
            $newStory->recruitment_attempt_id = $recruitmentAttempt->id;
            $newStory->attempt_status_id = $request->stop_recruitment_status;
            $newStory->comment = $request->stop_recruitment_comment;
            $newStory->created_at = date('Y-m-d H:i:s');
            $newStory->updated_at = date('Y-m-d H:i:s');

            $newStory->save();

            return 1;
        }
    }

    public function addToTraining(Request $request) {
        if ($request->ajax()) {
            return 1;
        }
    }
}
