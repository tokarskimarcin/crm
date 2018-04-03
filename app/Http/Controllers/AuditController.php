<?php

namespace App\Http\Controllers;


use App\AuditCriterions;
use App\AuditHeaders;
use App\AuditInfo;
use App\Department_info;
use App\User;
use Session;
use App\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class AuditController extends Controller
{

    /**
     * @return view addAudit, and info about departments, Audit Headers and Audit Criterions
     */
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
     * Save newly created audit to database (audit) and (audit_info)
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
            $arrFilename = $c->name . "_files";

                $newCrit = new AuditInfo();
                $newCrit->status = 1;
                $newCrit->audit_criterion_id = $c->id;
                $newCrit->audit_id = $newForm->id;
                $newCrit->amount = $request->$nameAmount;
                $newCrit->quality = $request->$nameQuality;
                $newCrit->comment = $request->$nameComment;
                $newCrit->save();




//            $files = $request->file($arrFilename);
//
//            if($request->hasFile($arrFilename))
//            {
//                foreach ($files as $file) {
//                    $file->store('users/' . $this->user->id . '/messages');
//                }
//            }
        }

        return Redirect::to('audit/'.$newForm->id);
    }

    /**
     * @return view showAudits and info about audit.
     */
    public function showAuditsGet(Request $request) {
            $audit = Audit::all();
        return view('audit.showAudits')->with('audit', $audit);
    }

    /**
     * @return Data for dataTable about all audits
     */
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
                CONCAT(trainer.first_name, " ", trainer.last_name) as trainer,
                audit.id as audit_id
                '));
            return datatables($audit)->make(true);
    }

    /**
     * @return view reviewAudit(edit) with related to this audit data(selected inputs, comments etc)
     */
    public function editAuditGet($id) {

        $infoAboutAudit = DB::table('audit')
            ->join('users', 'users.id', '=', 'audit.user_id')
            ->join('users as trainer', 'trainer.id', '=', 'audit.trainer_id')
            ->join('department_info', 'department_info.id', '=', 'audit.department_info_id')
            ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
            ->join('departments', 'departments.id', '=', 'department_info.id_dep')
            ->select(DB::raw('
                CONCAT(users.first_name, " ", users.last_name) as user_name,
                CONCAT(departments.name, " ", department_type.name) as department,
                date_audit,
                CONCAT(trainer.first_name, " ", trainer.last_name) as trainer,
                audit.id as audit_id
                '))
            ->where('audit.id', '=', $id)->get();


        $headers = AuditHeaders::where('status', '=', '1')->get();
        $criterion = AuditCriterions::where('status', '=', '1')->get();
        $audit_info = AuditInfo::where('audit_id', '=', $id)->get();
        $audit = Audit::find($id);

        return view('audit.reviewAudit')
            ->with('headers', $headers)
            ->with('criterion', $criterion)
            ->with('audit_info', $audit_info)
            ->with('audit', $audit)
            ->with('givenId', $id)
            ->with('infoAboutAudit', $infoAboutAudit);
    }

    /**
     * Method saves changes to given audit.
     */
    public function editAuditPost(Request $request) {
        $id = $request->givenID;
        $loggedUser = Auth::user();
        $audit = Audit::find($id);
        $audit->edit_user_id = $loggedUser->id;

        $criterions = AuditCriterions::all();
        foreach($criterions as $c) {
            $nameAmount = $c->name . "_amount";
            $nameQuality = $c->name . "_quality";
            $nameComment = $c->name . "_comment";
            $arrFilename = $c->name . "_files";
            $fileCatalog = $id;

            $crit = AuditInfo::where('audit_criterion_id','=', $c->id)->where('audit_id', '=', $id)->first(); //tylko 1 audit powinien byc
            $crit->amount = $request->$nameAmount;
            $crit->quality = $request->$nameQuality;
            $crit->comment = $request->$nameComment;
            $crit->save();

            $i = 0;
            $files = $request->file($arrFilename);

            //saving files to server
            if($request->hasFile($arrFilename))
            {
                foreach ($files as $file) {
                    $newArray = $request->files->all();
                    $fileName = $newArray[$arrFilename][0]->getClientOriginalName();
                    $dotIndex = strripos($fileName, '.'); //last occurence of .
                    $suffix = substr($fileName, $dotIndex); //rest of string after $dotIndex

                    $file->storeAs($fileCatalog, $c->name . '-' . $i . $suffix);
                    $i++;
                }
            }

        }
        return Redirect::to('audit/'.$id);
    }


}
