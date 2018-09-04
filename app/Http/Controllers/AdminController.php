<?php

namespace App\Http\Controllers;

use App\Audit;
use App\AuditCriterions;
use App\AuditHeaders;
use App\AuditStatus;
use App\Department_info;
use App\Department_types;
use App\Departments;
use App\HourReport;
use App\LinkGroups;
use App\Links;
use App\LogActionType;
use App\LogInfo;
use App\Pbx_report_extension;
use App\PrivilageRelation;
use App\PrivilageUserRelation;
use App\UserTypes;
use DeepCopy\f006\A;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\ActivityRecorder;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\User;
use App\Notifications;
use Illuminate\Support\Facades\URL;
use App\Firewall;
use App\FirewallPrivileges;
use App\UserTest;
use App\MedicalPackage;

class AdminController extends Controller
{
    public function check_all_tests() {
        return view('admin.all_tests');
    }

    public function datatableAllTests(Request $request) {
        $data = DB::table('user_tests')
            ->select(DB::raw('
                user_tests.*,
                first_name,
                last_name
            '))
            ->join('users', 'users.id', 'user_tests.cadre_id')
            ->get();

        return datatables($data)->make(true);
    }

    public function show_test_for_admin($id) {
        $test = UserTest::find($id);

        if ($test == null) {
            return view('errors.404');
        }

        return view('tests.testResult')
            ->with('test', $test);
    }

    /**
     * Edycja pakietów medycznych("trwałe" usuwanie)
     */
    public function edit_medical_package() {
        $months = collect([
            ['id' => '01', 'name' => 'Styczeń'],
            ['id' => '02', 'name' => 'Luty'],
            ['id' => '03', 'name' => 'Marzec'],
            ['id' => '04', 'name' => 'Kwiecień'],
            ['id' => '05', 'name' => 'Maj'],
            ['id' => '06', 'name' => 'Czerwiec'],
            ['id' => '07', 'name' => 'Lipiec'],
            ['id' => '08', 'name' => 'Sierpień'],
            ['id' => '09', 'name' => 'Wrzesień'],
            ['id' => '10', 'name' => 'Październik'],
            ['id' => '11', 'name' => 'Listopad'],
            ['id' => '12', 'name' => 'Grudzień']
        ]);

        return view('admin.editMedicalPackages')
            ->with('months', $months);
    }

    /**
     * Pobranie danych miesięcznych na temat pakietów medycznych
     */
    public function getMedicalPackagesAdminData(Request $request) {
        if ($request->ajax()) {
            $date = $request->year_selected . '-' . $request->month_selected . '%';
            $data = MedicalPackage::where('created_at', 'like', $date)
                ->get();

            return $data;
        }
    }

    /**
     * Pobranie danych dla pojedyńczego pakeitu medycznego
     */
    public function getMedicalPackageData(Request $request) {
        if ($request->ajax()) {
            return MedicalPackage::find($request->id);
        }
    }

    /**
     * Zapis danych dla pakeitu medycznego
     */
    public function saveMedicalPackageData(Request $request) {
        if ($request->ajax()) {
            $data = [];
            $package = MedicalPackage::find($request->package_id);

            $package->user_id           = $request->user_id;
            $package->user_first_name   = $request->user_first_name;
            $package->user_last_name    = $request->user_last_name;
            $package->pesel             = $request->pesel;
            $package->birth_date        = $request->birth_date;
            $package->city              = $request->city;
            $package->street            = $request->street;
            $package->house_number      = $request->house_number;
            $package->flat_number       = $request->flat_number;
            $package->postal_code       = $request->postal_code;
            $package->phone_number      = $request->phone_number;
            $package->package_name      = $request->package_name;
            $package->package_variable  = $request->package_variable;
            $package->package_scope     = $request->package_scope;
            $package->month_start       = $request->month_start;
            $package->month_stop        = $request->month_stop;
            $package->deleted           = $request->deleted;

            $data['ID użytkownika'] = $request->user_id;
            $data['Imię'] = $request->user_first_name;
            $data['Nazwisko'] = $request->user_last_name;
            $data['PESEL'] = $request->pesel;
            $data['Data urodzenia'] = $request->birth_date;
            $data['Miasto'] = $request->city;
            $data['Ulica'] = $request->street;
            $data['Nr domu'] = $request->house_number;
            $data['Nr mieszkania'] = $request->flat_number;
            $data['Kod pocztowy'] = $request->postal_code;
            $data['Nr tel'] = $request->phone_number;
            $data['Pakiet'] = $request->package_name;
            $data['Wariant'] = $request->package_variable;
            $data['Zakres']  = $request->package_scope;
            $data['Rozpoczęcie'] = $request->month_start;
            $data['Zakończenie'] = $request->month_stop;
            $data['Usunięty'] = $request->deleted;
            $data['Usunięty trwale'] = $request->hard_deleted;

            if ($request->hard_deleted == 1) {
                $package->hard_deleted  = 1;
            } else {
                $package->hard_deleted  = null;
            }
            $package->updated_at        = date('Y-m-d H:i:s');
            $package->updated_by        = Auth::user()->id;

            $package->save();

            new ActivityRecorder($data,130,2);
            return 1;
        }
    }

    /**
     * This method is responsible for sending data about headers for a given template
     * @arg $id = id of template (audit_status->id)
     */
    public function editAuditGet($id) {
        $headers = AuditHeaders::where('status', '=', $id)->get();
        return view('admin.editAudit')->with('headers', $headers)->with('status', $id);
    }

    /**
     * This method is responsible for sending data about criterions for a given header by ajax
     */
    public function editAuditPost(Request $request) {
        $criterions = AuditCriterions::where('audit_header_id', '=', $request->header_id)->where('status', '=', $request->status)->get();
        return $criterions;
    }

    /**
     * This function is responsible for adding/removing Headers AND Criterions for given audits templates
     */
    public function editDatabasePost(Request $request) {
        $addingHeader = $request->addingHeader;
        $addingCrit = $request->addingCrit;

        if($addingCrit == "true") {
            $newName = mb_strtolower(str_replace('/','_',str_replace(' ', '_', trim($request->newCritName, ' '))), 'UTF-8');
            $newCriterium = new AuditCriterions();
            $newCriterium->name = $newName;
            $newCriterium->audit_header_id = $request->relatedHeader;
            $newCriterium->status = $request->status;
            $newCriterium->save();

            $log = Array("T " => "Dodanie kryterium");
            $log = array_merge($log, $newCriterium->toArray());
            new ActivityRecorder($log, 171,1);
        }

        else if($addingCrit == "false") {
            $critToRemove = AuditCriterions::where('id', '=', $request->cID)->first();
            $critToRemove->status = 0;
            $critToRemove->save();
            $log = Array("T " => "Usunięcie kryterium");
            $log = array_merge($log, $critToRemove->toArray());
            new ActivityRecorder($log, 171,3);
        }

        else if($addingHeader == "true") {
            $newName = mb_strtolower(trim($request->newHeaderName, ' '), 'UTF-8');
            $newHeader = new AuditHeaders();
            $newHeader->name = $newName;
            $newHeader->status = $request->status;
            $newHeader->save();
            $log = Array("T " => "Dodanie nagłówka");
            $log = array_merge($log, $newHeader->toArray());
            new ActivityRecorder($log, 171,1);
        }
        else if($addingHeader == "false") {
            $headerToRemove = AuditHeaders::where('id', '=', $request->hid)->first();
            $relatedCriterions = AuditCriterions::where('audit_header_id', '=', $request->hid)->where('status', '=', $request->status)->get();
            $headerToRemove->status = 0;
            $headerToRemove->save();
            $log = Array("T " => "Usunięcie nagłówka");
            $log = array_merge($log, $headerToRemove->toArray());
            new ActivityRecorder($log, 171,3);
            foreach($relatedCriterions as $rC) {
                $rC->status = 0;
                $rC->save();
            }
        }
        return Redirect::back();
    }

    /**
     * This method is responsible for sending all data about templates to editAuditTempalte view
     */
    public function editAuditTemplatesGet() {
        $allTemplates = AuditStatus::where('isActive', '=', '1')->get();
        return view('admin.editAuditTemplates')->with('templates', $allTemplates);
    }

    /**
     * This method is responsible for adding/removing audit templates
     */
    public function addTemplatePost(Request $request) {
        $isAdding = $request->isAdding;
        if($isAdding == null) { //condition satisfied when user is only adding new template
            $templateName = $request->templateName;
            $newTemplate = new AuditStatus();
            $newTemplate->name = trim($templateName, ' ');
            $newTemplate->isActive = 1;
            $newTemplate->save();

            $log = Array("T " => "Dodanie szablonu");
            $log = array_merge($log, $newTemplate->toArray());

            new ActivityRecorder($log, 163,1);
        }
        else { //condition satisfied when user is deleting given template
            $idToDelete = $request->idToDelete;
            $templateToDelete = AuditStatus::where('id', '=', $idToDelete)->first();
            $templateToDelete->isActive = 0;
            $templateToDelete->save();
            $log = Array("T " => "Usunięcie szablonu");
            $log = array_merge($log, $templateToDelete->toArray());
            new ActivityRecorder($log, 163,3);
        }

        return Redirect::back();
    }
}
