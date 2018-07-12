<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ActivityRecorder extends Model
{
    private $user;
    private $date;
    private $action;
    private $logTypes = [
        'hrActivity.txt',
        'financesActivity.txt',
        'adminActivity.txt',
        'jankyActivity.txt',
        'workHoursActivity.txt',
        'equipmentActivity.txt',
        'activity.txt',
        'recruitmentActivity.txt'
    ];

    /*
      you can pass strings, integers or Arrays
      if you pass array it will automaticly convert it into strig
      example:
      $data = [
          'name' => 'genowefa',
          'age' => 69
      ];

      it will output 'name : genowegfa. age : 69.'

      log should look like ID: [44] DATE: [2017-11-28 07:37:40] ACTION: [name : genowegfa. age : 69.]

      files are stored in storage/app

      types of activity:
      1 - HR managment
      2 - finances
      3 - admin activity
      4 - janky activity
      5 - work hours actiity
      6 - equipment Activity
      7 - other
      8 - recruitment activity
      9 - medical packages
      10 - audits
      12 - CRM
    */

//    public function __construct($type, $action, $link_id = null, $action_id = null)
//    {
//        $this->user = Auth::user()->id;
//        $type = 1;
//        $this->date = date("Y-m-d H:i:s");
//        $this->action = $action;
//
//        $string = '';
//
//        if (is_array($this->action)) {
//            foreach ($this->action as $key => $value) {
//                $string .= $key . ' : ' . $value . ', ';
//            }
//        } else {
//            $string = $this->action;
//        }
//
//        $content = $string;
//
//        switch ($type) {
//            case '1':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '2':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '3':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '4':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '5':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '6':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '7':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '8':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '9':
//                $contents = Storage::get('medicalPackagesActivity.txt');
//                $size = File::size(storage_path('app/medicalPackagesActivity.txt'));
//                if ($size < 104857600) {
//                    Storage::append('medicalPackagesActivity.txt', $content);
//                }
//                break;
//
//            case '10':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '11':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            case '12':
//                $newLog = new Logs();
//                $newLog->links_id = $link_id;
//                $newLog->user_id = $this->user;
//                $newLog->action_type_id = $action_id;
//                $newLog->comment = $content;
//                $newLog->save();
//                break;
//
//            default:
//
//                break;
//        }
//    }

    public function __construct($action, $link_id = null, $action_id = null)
    {
        $this->user = Auth::user()->id;
        $type = 1;
        $this->date = date("Y-m-d H:i:s");
        $this->action = $action;

        $string = '';

        if (is_array($this->action)) {
            $this->appendingKeyValueToString($string, $this->action);
            $string = rtrim($string, ', ');
        } else {
            $string = $this->action;
        }

        $content = $string;

        $newLog = new Logs();
        $newLog->links_id = $link_id;
        $newLog->user_id = $this->user;
        $newLog->action_type_id = $action_id;
        $newLog->comment = $content;
        $newLog->save();
    }

    private function appendingKeyValueToString(&$string, $data){
        foreach ($data as $key => $value) {
            if(is_array($value)){
                $string .= $key . ': ';
                $string.='[';
                $this->appendingKeyValueToString($string, $value);
                $string = rtrim($string, ', ');
                $string.='], ';
            }else {
                $string .= $key . ': ' . $value . ', ';
            }
        }
    }

}
//        // foreach($this->logTypes as $type) {
//        //     $file = File::get(storage_path('app/' . $type));
//        //     $how_much = strlen($file);
//        //     $contents = substr($file, $how_much / 2 );
//        //     Storage::put($type, $contents);
//        // }
//
//        /** CHWILOWO WYŁĄCZONE KASOWANIE DANYCH Z PLIKU */
//        // $day = date('d');
//        // if ($day == 1) {
//        //     $this->clearLogs();
//        // }

//    private function clearLogs() {
//        $checkIfCleared = File::get(storage_path('app/logData.txt'));
//        $pos = strpos($checkIfCleared, date('y-m'));
//        if (!$pos) {
//            foreach($this->logTypes as $type) {
//                $file = File::get(storage_path('app/' . $type));
//                $how_much = strlen($file);
//                $contents = substr($file, $how_much / 2 );
//                Storage::put($type, $contents);
//            }
//
//            $contents = Storage::get('logData.txt');
//            Storage::append('logData.txt', date('Y-m'));
//        }
//
//
//    }


