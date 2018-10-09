<?php
/**
 * Created by PhpStorm.
 * User: veronaprogramista
 * Date: 09.10.18
 * Time: 14:32
 */

namespace App\Http\Controllers\AdminPanel;


class NotificationSystemController
{
    public function notificationSystemGet(){
        return view('admin.notificationSystem');
    }
}