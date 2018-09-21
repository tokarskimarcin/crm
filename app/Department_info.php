<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department_info extends Model
{
    protected $table = 'department_info';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_dep','id_dep_type','size','commission_avg','commission_hour','commission_start_money','commission_step',
        'dep_aim','dep_aim_week','commission_janky','type','janky_system_id','pbx_id','working_hours_normal','working_hours_week'
    ];

    public function users() {
       return $this->hasMany('App\User');
    }

    public function departments() {
        return $this->belongsTo('App\Departments', 'id_dep');
    }

    public function department_type() {
        return $this->belongsTo('App\Department_types', 'id_dep_type');
    }

    public function summaryPayment() {
        return $this->hasMany('App\SummaryPayment', 'department_info_id');
    }

    public function equipments() {
        return $this->hasMany('App\Equipments', 'department_info_id');
    }

    public function notifications() {
        return $this->hasMany('App\Notifications', 'department_info_id');
    }

    public function employee_of_the_week() {
        return $this->hasMany('App\EmployeeOfTheWeek', 'department_info_id');
    }

    public function multiple_departments(){
        return $this->hasMany('App\MultipleDepartments', 'department_info_id');
    }

    public function menager() {
        return $this->belongsTo('App\User', 'menager_id');
    }
    public function director() {
        return $this->belongsTo('App\User', 'director_id');
    }

    public static function getAllInfoAboutDepartment(){
        $departments = Department_info::all();
        $departments->map(function ($item){
            $item->department_name = $item->departments->name;
            $item->type_name = $item->department_type->name;
            return $item;
        });
        return $departments;
    }


    /** Edit or modify department
     * @param $request
     * @return Department_info|\Illuminate\Support\Collection
     */
    public static function addModifyDepartment($request){

        //tutaj sprawdzenie czy oddział jest dodawany do istniejącego miasta czy stworzono  nowy
        if ($request->department != '-1') {
            $id_dep = $request->department;
        } else {
            $department = new Departments();

            $department->name = $request->city;
            $department->desc = $request->desc;
            $department->save();

            $departments = Departments::
            orderBy('id', 'desc')
                ->limit(1)
                ->get();

            $id_dep = $departments[0]->id;
        }
        if(isset($request->selected_department_info_id))
            $department_info =Department_info::find($request->selected_department_info_id);
        else{
            $department_info = new Department_info();
            $department_info->id_dep                    = $id_dep;
        }
        $department_info->id_dep_type               = $request->id_dep_type;
        $department_info->size                      = ($request->size != null) ? $request->size : 0 ;
        $department_info->commission_avg            = ($request->commission_avg) ? $request->commission_avg : 0 ;
        $department_info->commission_hour           = ($request->commission_hour) ? $request->commission_hour : 0 ;
        $department_info->commission_step           = ($request->commission_step) ? $request->commission_step : 0 ;
        $department_info->commission_start_money    = ($request->commission_start_money) ? $request->commission_start_money : 0 ;
        $department_info->commission_janky          = ($request->commission_janky) ? $request->commission_janky : 0 ;
        $department_info->dep_aim                   = ($request->dep_aim) ? $request->dep_aim : 0 ;
        $department_info->dep_aim_week              = ($request->dep_aim_week) ? $request->dep_aim_week : 0 ;
        $department_info->type                      = ($request->type != 'Wybierz') ? $request->type : '' ;
        $department_info->janky_system_id           = ($request->janky_system_id) ? $request->janky_system_id : 0 ;
        $department_info->pbx_id                    = ($request->pbx_id) ? $request->pbx_id : 0 ;
        $department_info->working_hours_normal      = ($request->work_hour > 0) ? $request->work_hour : 0 ;
        $department_info->working_hours_week        = ($request->work_hour_weekend > 0) ? $request->work_hour_weekend : 0 ;
        $department_info->blocked                   = 0;
        $department_info->menager_id                = ($request->menager != 0) ? $request->menager : null ;
        $department_info->director_id               = ($request->director != 0) ? $request->director : null ;
        $department_info->director_hr_id            = ($request->director_hr != 0) ? $request->director_hr : null;
        $department_info->hr_id                     = ($request->hrEmployee != 0) ? $request->hrEmployee : null ;
        $department_info->hr_id_second              = ($request->hrEmployee2 != 0) ? $request->hrEmployee2 : null ;
        $department_info->regionalManager_id        = ($request->regionalManager != 0) ? $request->regionalManager : null ;
        $department_info->instructor_regional_id    = ($request->regionalManagersInstructors != 0) ? $request->regionalManagersInstructors : null ;
        try{
            $department_info->save();
        }catch (\Exception $exception){
            return collect();
        }
        return $department_info;
    }
}
