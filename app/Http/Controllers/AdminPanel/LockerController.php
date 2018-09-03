<?php

namespace App\Http\Controllers\AdminPanel;

use App\ActivityRecorder;
use App\Department_info;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//Lock Department when PIP is comming
class LockerController extends Controller
{
    /**
     * Show all departments to lock
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lockerGet() {
        $department_info = Department_info::all();
        return view('admin.locker')
            ->with('department_info', $department_info);
    }

    /**
     * Ajax lock department
     * @param Request $request
     * @return int|string
     */
    public function lockerPost(Request $request) {
        if($request->ajax()) {
            $data = [];
            $department_info_id = Department_info::find($request->department_info_id);
            if ($department_info_id == null) {
                return 0;
            } else {
                $department_info_id->blocked = $request->type;
                try{
                    $department_info_id->save();
                }catch (\Exception $exception){
                    return 'Błąd poczas wykonywania SQL';
                }
                $data['T']              = 'Zmiana status oddziału';
                $data['ID oddziału']    = $department_info_id->id;
                $data['Status']         = $request->type;
                new ActivityRecorder($data, 50, 4);
                return 1;
            }
        }
    }
}
