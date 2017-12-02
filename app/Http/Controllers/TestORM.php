<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\User;
use App\Department_info;
use App\Department_types;
use App\Departments;
use App\UserTypes;
use App\Agencies;
use App\Schedule;
use App\PenaltyBonus;
use App\Work_hour;
use App\Dkj;
use App\Links;
use App\LinkGroups;
use App\PrivilageRelation;
use App\SummaryPayment;
use App\EquipmentTypes;
use App\Equipments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\MultipleDepartments;
use App\ActivityRecorder;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Mail;
use App\Mail\RaportMail;
use Mail;
use Request;

class TestORM extends Controller
{
    public function test() {


        Mail::send('404', array('key' => 'value'), function($message)
        {
            //MAIL_DRIVER=mail w env
            // 'sendmail' => '/usr/sbin/sendmail -bs', na
           // -> mail.php  'sendmail' => "C:\xampp\sendmail\sendmail.exe\ -t",
            $message->from('skobry123on@gmail.com');
            $message->to('skobry123on@gmail.com', 'John Smith')->subject('Welcome!');
        });

    }

}
