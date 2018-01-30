<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AttemptStatus;

class RecruitmentAttemptController extends Controller
{

    /**
     * Funkcja zwracająca wszystkie etapy rekrutacji
     */
    public function getAttemptLevel(Request $request) {
        $data = AttemptStatus::all();

        return $data;
    }

    /**
     * Funkcja dodająca kolejny etap rekrutacji
     */
    public function addAttemptLevel(Request $request) {
        $attempt_status = new AttemptStatus();

        $attempt_status->name = $request->name;
        $attempt_status->status = 0;
        $attempt_status->created_at = date('Y-m-d H:i:s');
        $attempt_status->updated_at = date('Y-m-d H:i:s');
        $attempt_status->save();

        return 1;
    }

    public function add_candidate() {
        return view('recruitment.candidateProfile');
    }
}
