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
        Mail::send(['text' => 'mail.raport'], ['name'=>'konrad'], function($message){
          $message->to('jarzyna.verona@gmail.com', 'To konrad')->subject('test email');
          $message->from('jarzyna.verona@gmail.com', 'Konrad');
        });
    }

}
