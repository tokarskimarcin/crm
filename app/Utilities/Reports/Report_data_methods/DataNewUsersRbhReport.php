<?php
/**
 * Created by PhpStorm.
 * User: Verona
 * Date: 15.10.2018
 * Time: 10:51
 */

namespace App\Utilities\Reports\Report_data_methods;


use App\Rbh30Report;
use Illuminate\Support\Facades\DB;

class Data30RBHreport
{
    public static function get($date_start, $date_stop, $groupByType = 0) {
        $maxIds = DB::table('rbh_30_report')
            ->select(DB::raw('
                    MAX(id) as id
                '))
            ->groupBy('user_id')
            ->where([
                ['created_at', '>=', $date_start],
                ['created_at', '<=', $date_stop]
            ])
            ->pluck('id')->toArray();

        //All most recent records from given range
        $data = Rbh30Report::select(
            DB::raw('CONCAT(departments.name, " ", department_type.name) as department_info_id'),
            'department_info.id as id',
            'first_name',
            'last_name',
            'success',
            'sec_sum'
        )
            ->join('users', 'rbh_30_report.user_id', '=', 'users.id')
            ->join('department_info', 'rbh_30_report.department_info_id', '=', 'department_info.id')
            ->join('departments', 'department_info.id_dep', '=', 'departments.id')
            ->join('department_type', 'department_info.id_dep_type', '=', 'department_type.id')
            ->whereIn('rbh_30_report.id', $maxIds)
            ->orderBy('department_info_id')
            ->orderBy('success', 'DESC')
            ->get();

        $dataGroupedByDepartment = null;
        if($groupByType == 0) {
            $dataGroupedByDepartment = $data->groupBy('department_info_id');
        }
        else if($groupByType == 1) {
            $dataGroupedByDepartment = $data->groupBy('id');
        }

        return $dataGroupedByDepartment;
    }
}
