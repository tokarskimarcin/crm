<?php

namespace App\Http\Controllers;


use App\AuditCriterions;
use App\AuditHeaders;
use App\AuditInfo;
use App\Department_info;
use App\User;
use App\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Session;

class AuditController extends Controller
{


    public function auditMethodGet() {
        $dept = Department_info::all();
        $headers = AuditHeaders::where('status', '=', '1')->get();
        $criterion = AuditCriterions::where('status', '=', '1')->get();

        return view('audit.addAudit')->with('dept', $dept)->with('headers', $headers)->with('criterion', $criterion);
    }

    /**
     * Ajax responsible for sending data about trainers for 2nd step - "select" field in addAudit
     */
    public function ajax(Request $request) {
        $trainers = User::whereIn('user_type_id', [4,12])->where('department_info_id', '=', $request->wybranaOpcja)->where('status_work', '=', '1')->get();
        return $trainers;
    }


    /**
     * Save audit to database (audit) and (audit_info)
     */
    public function handleFormPost(Request $request) {
        $newForm = new Audit();
        $user = Auth::user();

        /*Fil "audit" table*/
        $newForm->user_id = $user->id;
        $newForm->trainer_id = $request->trainer;
        $newForm->department_info_id = $request->department_info;
        $newForm->date_audit = $request->date;
        $newForm->save();

        /*fill "audit_info" table*/
        $criterions = AuditCriterions::all();
        foreach($criterions as $c) {
            $nameAmount = $c->name . "_amount";
            $nameQuality = $c->name . "_quality";
            $nameComment = $c->name . "_comment";

                $newCrit = new AuditInfo();
                $newCrit->status = 1;
                $newCrit->audit_criterion_id = $c->id;
                $newCrit->audit_id = $newForm->id;
                $newCrit->amount = $request->$nameAmount;
                $newCrit->quality = $request->$nameQuality;
                $newCrit->comment = $request->$nameComment;
                $newCrit->save();
        }
    }

    public function showAuditsGet(Request $request) {
        return view('audit.showAudits');
    }

    public function showAuditsPost(Request $request) {

            $audit = DB::table('audit')
                ->join('users', 'users.id', '=', 'audit.user_id')
                ->join('users as trainer', 'trainer.id', '=', 'audit.trainer_id')
                ->join('department_info', 'department_info.id', '=', 'audit.department_info_id')
                ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
                ->join('departments', 'departments.id', '=', 'department_info.id_dep')
                ->select(DB::raw('
                CONCAT(users.first_name, " ", users.last_name) as user_name,
                CONCAT(departments.name, " ", department_type.name) as department,
                date_audit,
                trainer.first_name as trainer_first_name,
                trainer.last_name as trainer_last_name            
                '));

            return datatables($audit)->make(true);
    }
}
