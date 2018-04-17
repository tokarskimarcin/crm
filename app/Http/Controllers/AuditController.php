<?php

namespace App\Http\Controllers;


use App\AuditCriterions;
use App\AuditEdit;
use App\AuditHeaders;
use App\AuditInfo;
use App\AuditStatus;
use App\Department_info;
use App\User;
use App\AuditFiles;
use Illuminate\Support\Facades\Storage;
use Session;
use App\Audit;
use App\ActivityRecorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class AuditController extends Controller
{

    /**
     * @return view addAudit and info about departments, Audit Headers and Audit Criterions
     */
    public function auditMethodGet() {
        $dept = Department_info::whereIn('id_dep_type', [1,2,6])->get();
        $headers = AuditHeaders::all(); //there was where(status = 1)
        $criterion = AuditCriterions::where('status', '=', '1')->get();
        $templates = AuditStatus::all();

        return view('audit.addAudit')
            ->with('dept', $dept)
            ->with('headers', $headers)
            ->with('criterion', $criterion)
            ->with('templates', $templates);
    }


    /**
     * This method returns form to fill with necessary data
     */
    public function auditMethodPost(Request $request) {
        $typeOfPerson = $request->typeOfPerson; // 1 - trainer, 2 - hr, 3 - collective
        $user = Auth::user();
        $templateType = $request->template;
        $headers = AuditHeaders::all(); //there was where(status = 1)
        $criterion = AuditCriterions::where('status', '=', $templateType)->get();

        return view('audit.newAudit')
            ->with('templateType', $templateType)
            ->with('headers', $headers)
            ->with('trainerID', $request->trainer)
            ->with('department_info', $request->department_info)
            ->with('date_audit', $request->date)
            ->with('criterion', $criterion)
            ->with('typeOfPerson', $typeOfPerson);
    }

    /**
     * Ajax responsible for sending data about trainers for 2nd step - "select" field in addAudit
     */
    public function ajax(Request $request) {
        $trainers = User::whereIn('user_type_id', [4,12])->where('department_info_id', '=', $request->wybranaOpcja)->where('status_work', '=', '1')->get();
        $hr = User::where('user_type_id', '=', '5')->where('department_info_id', '=', $request->wybranaOpcja)->where('status_work', '=', '1')->get();
        $collective = User::where('user_type_id', '=', '7')->where('department_info_id', '=', $request->wybranaOpcja)->where('status_work', '=', '1')->first();
        $arr = array("trainers" => $trainers, "hr" => $hr, "collective" => $collective);
        return $arr;
    }

    /**
     * Save newly created audit to database (audit) and (audit_info)
     */
    public function handleFormPost(Request $request) {
        $auditPercentScore = $request->score;
        $newForm = new Audit();
        $user = Auth::user();
        $template = $request->templateType;

        /*Fil "audit" table*/
        $newForm->user_id = $user->id;
        $newForm->trainer_id = $request->trainer;
        $newForm->user_type = $request->typeOfPerson; // 1 - trainer, 2 - hr, 3 - collective
        $newForm->department_info_id = $request->department_info;
        $newForm->date_audit = $request->date;
        $newForm->score = round($auditPercentScore, 2);
        $newForm->save();

        $fileCatalog = "auditFiles";
        $suffix = '';


        /*fill "audit_info" table*/
        $criterions = AuditCriterions::where('status', '=', $template)->get();
        foreach($criterions as $c) {
            $nameAmount = $c->name . "_amount";
//            $nameQuality = $c->name . "_quality";
            $nameComment = $c->name . "_comment";
            $arrFilename = $c->name . "_files";

            $newCrit = new AuditInfo();
            $newCrit->status = 1;
            $newCrit->audit_criterion_id = $c->id;
            $newCrit->audit_id = $newForm->id;
            $newCrit->amount = $request->$nameAmount;
//            $newCrit->quality = $request->$nameQuality;
            $newCrit->comment = $request->$nameComment;


            //part responsible for saving files from user
            $files = $request->file($arrFilename);

            if($request->hasFile($arrFilename))
            {

                foreach ($files as $file) {
                    $newArray = $request->files->all();
                    $fileName = $file->getClientOriginalName();
                    $dotIndex = strripos($fileName, '.'); //last occurence of .
                    $suffix = strtolower(substr($fileName, $dotIndex)); //rest of string after $dotIndex

                    if($suffix == '.jpeg' || $suffix == '.jpg' || $suffix == '.png' || $suffix == '.pdf') {
                        $audit_files = new AuditFiles();
                        $audit_files->audit_id = $newForm->id;
                        $audit_files->criterion_id = $c->id;
                        $audit_files->save();
                        $nameOfFile = $newForm->id . '-' . $c->name . '-' . $audit_files->id . $suffix;
                        $audit_files->name = $nameOfFile;
                        $audit_files->save();
                        $file->storeAs($fileCatalog, $newForm->id . '-' . $c->name . '-' . $audit_files->id . $suffix);
                    }
                }
            }
            $newCrit->save();
            Session::flash('adnotation', "Audyt został dodany!");
        }
        return Redirect::to('/showAudits');
    }

    /**
     * @return view showAudits and info about audit.
     */
    public function showAuditsGet(Request $request) {
            $audit = Audit::all();
            $departments = Department_info::all();
            $directors = User::where('user_type_id', '=', '7')->where('status_work', '=', '1')->get();
        return view('audit.showAudits')->with('audit', $audit)->with('departments', $departments)->with('directors', $directors);
    }

    /**
     * @return Data for dataTable about all audits
     */
    public function showAuditsPost(Request $request) {
//        dd($request);
            $audit = DB::table('audit')
                ->join('users', 'users.id', '=', 'audit.user_id')
                ->join('users as trainer', 'trainer.id', '=', 'audit.trainer_id')
                ->join('department_info', 'department_info.id', '=', 'audit.department_info_id')
                ->join('department_type', 'department_type.id', '=', 'department_info.id_dep_type')
                ->join('departments', 'departments.id', '=', 'department_info.id_dep')
                ->select(DB::raw('
                users.first_name as user_first_name,
                users.last_name as user_last_name,
                departments.name as department_name,
                department_type.name as department_type,
                date_audit,
                trainer.first_name as trainer_first_name,
                trainer.last_name as trainer_last_name,
                audit.id as audit_id,
                audit.user_type as user_type,
                audit.score
                '))

                ->whereBetween('date_audit', [$request->date_start, $request->date_stop]);
        if($request->department != null && $request->department != '0') {
            $audit = $audit ->where('department_info.id', '=', $request->department);
        }
        if($request->director != null && $request->director != '0') {
            $audit = $audit ->where('department_info.director_id' , '=', $request->director);
        }
        if($request->type != null && $request->type != '0') {
            $audit = $audit ->where('audit.user_type', '=', $request->type);
        }
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


        $selectedCrits = DB::table('audit_info')
            ->join('audit_criterion', 'audit_criterion.id', '=', 'audit_info.audit_criterion_id')
        ->select(DB::raw('
            audit_criterion.audit_header_id as audit_header_id,
            audit_criterion.name as name,
            audit_criterion.id as id
        '))
            ->where('audit_id', '=', $id)->get();
        $headers = AuditHeaders::all();
//        $criterion = AuditCriterions::where('status', '=', '1')->Audit_info->get();
        $audit_info = AuditInfo::where('audit_id', '=', $id)->get();
        $audit_files = AuditFiles::where('audit_id', '=', $id)->get();
        $audit = Audit::find($id);

        return view('audit.reviewAudit')
            ->with('headers', $headers)
            ->with('criterion', $selectedCrits)
            ->with('audit_info', $audit_info)
            ->with('audit', $audit)
            ->with('givenId', $id)
            ->with('audit_files', $audit_files)
            ->with('infoAboutAudit', $infoAboutAudit);
    }

    /**
     * Method saves changes to given audit.
     */
    public function editAuditPost(Request $request) {
        $id = $request->givenID;
        $today = date('Y-m-d');
        $loggedUser = Auth::user();
        $audit = Audit::find($id);
        $audit->edit_user_id = $loggedUser->id;
        $audit->score = round($request->score, 2);
        $audit->save();

        //Saving info about edition to log file
        $log = [
            "ID edytowanego audytu" => $audit->id,
            "ID osoby edytujacej" => $loggedUser->id,
            "Data edycji" => $today
        ];
        new ActivityRecorder(3, $log);

        $criterions = AuditCriterions::all();
        foreach($criterions as $c) {
            //seting names for input identification purposes
            $nameAmount = $c->name . "_amount";
//            $nameQuality = $c->name . "_quality";
            $nameComment = $c->name . "_comment";
            $arrFilename = $c->name . "_files";
            $fileCatalog = "auditFiles";
            $suffix = '';

            $crit = AuditInfo::where('audit_criterion_id','=', $c->id)->where('audit_id', '=', $id)->first();
            if($crit == null) { //occurs when foreach reach criterion that at the moment has status 1, but in time of audit creation had status = 0 or didn't exist yet
                continue;
            }
            $crit->amount = $request->$nameAmount;
//            $crit->quality = $request->$nameQuality;
            $crit->comment = $request->$nameComment;

            //part responsible for uploading files from user
            $files = $request->file($arrFilename);

            if($request->hasFile($arrFilename))
            {

                foreach ($files as $file) {
                    $newArray = $request->files->all();
                    $fileName = $file->getClientOriginalName();
                    $dotIndex = strripos($fileName, '.'); //last occurence of .
                    $suffix = strtolower(substr($fileName, $dotIndex)); //rest of string after $dotIndex

                    if ($suffix == '.jpeg' || $suffix == '.jpg' || $suffix == '.png' || $suffix == '.pdf') {
                        $audit_files = new AuditFiles();
                        $audit_files->audit_id = $audit->id;
                        $audit_files->criterion_id = $c->id;
                        $audit_files->save();
                        $nameOfFile = $id . '-' . $c->name . '-' . $audit_files->id . $suffix; //name = id + input name + id(from audit_files) + file extension
                        $audit_files->name = $nameOfFile;
                        $audit_files->save();
                        $file->storeAs($fileCatalog, $id . '-' . $c->name . '-' . $audit_files->id . $suffix);
                    }
                }
            }
            $crit->save();
        }
        return Redirect::to('audit/'.$id);
    }


}
