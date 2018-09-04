<?php

namespace App\Http\Controllers\AdminPanel;

use App\LinkGroups;
use App\LogActionType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    /** Show view with loginfo
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logInfoGet()
    {
        $linkGroups = LinkGroups::all();
        $logActionType = LogActionType::all();
        return view('admin.logInfo')
            ->with('linkGroups', json_encode($linkGroups))
            ->with('logActionType', json_encode($logActionType));
    }

    /** Get ajax data about logs
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function datatableLogInfoAjax(Request $request)
    {
        $operatorActionType = '<>';
        if ($request->action_type_id > 0)
            $operatorActionType = '=';

        $operatorGroupLink = '<>';
        if ($request->group_link_id > 0)
            $operatorGroupLink = '=';
        $logs = DB::table('log_info as lf')
            ->select('u.first_name', 'u.last_name', 'l.link', 'la.name as action_name', 'lf.updated_at', 'lf.comment')
            ->leftJoin('log_action_type as la', 'lf.action_type_id', '=', 'la.id')
            ->leftJoin('links as l', 'lf.links_id', '=', 'l.id')
            ->leftJoin('users as u', 'lf.user_id', '=', 'u.id')
            ->where('lf.action_type_id', $operatorActionType, $request->action_type_id)
            ->where('l.group_link_id',$operatorGroupLink, $request->group_link_id)
            ->whereBetween('lf.updated_at', [$request->fromDate, $request->toDate . ' 23:59:59'])
            ->get();
        return datatables($logs)->make(true);
    }
}
