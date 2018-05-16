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
    */

    public function __construct($type, $action) {
        $this->user = Auth::user()->id;
        $this->date = date("Y-m-d H:i:s");
        $this->action = $action;

        $string = '';

        if (is_array($this->action)) {
            foreach($this->action as $key => $value) {
                $string .= $key . ' : ' . $value . ', ';
            }
        } else {
            $string = $this->action;
        }

        $content = 'ID: [' . $this->user . '] DATE: [' . $this->date . '] ACTION: [' . $string . ']';

        switch ($type) {
          case '1':
            $contents = Storage::get('hrActivity.txt');
            $size = File::size(storage_path('app/hrActivity.txt'));
            if ($size < 104857600) {
                Storage::append('hrActivity.txt', $content);
            }
            break;

          case '2':
            $contents = Storage::get('financesActivity.txt');
            $size = File::size(storage_path('app/financesActivity.txt'));
            if ($size < 104857600) {
                Storage::append('financesActivity.txt', $content);
            }
            break;

          case '3':
            $contents = Storage::get('adminActivity.txt');
            $size = File::size(storage_path('app/adminActivity.txt'));
            if ($size < 104857600) {
                Storage::append('adminActivity.txt', $content);
            }
            break;

          case '4':
            $contents = Storage::get('jankyActivity.txt');
            $size = File::size(storage_path('app/jankyActivity.txt'));
            if ($size < 104857600) {
                Storage::append('jankyActivity.txt', $content);
            }
            break;

          case '5':
            $contents = Storage::get('workHoursActivity.txt');
            $size = File::size(storage_path('app/workHoursActivity.txt'));
            if ($size < 104857600) {
                Storage::append('workHoursActivity.txt', $content);
            }
            break;

          case '6':
            $contents = Storage::get('equipmentActivity.txt');
            $size = File::size(storage_path('app/equipmentActivity.txt'));
            if ($size < 104857600) {
                Storage::append('equipmentActivity.txt', $content);
            }
            break;

          case '7':
            $contents = Storage::get('activity.txt');
            $size = File::size(storage_path('app/activity.txt'));
            if ($size < 104857600) {
                Storage::append('activity.txt', $content);
            }
            break;

          case '8':
            $contents = Storage::get('recruitmentActivity.txt');
            $size = File::size(storage_path('app/recruitmentActivity.txt'));
            if ($size < 104857600) {
                Storage::append('recruitmentActivity.txt', $content);
            }
            break;

            case '9':
                $contents = Storage::get('medicalPackagesActivity.txt');
                $size = File::size(storage_path('app/medicalPackagesActivity.txt'));
                if ($size < 104857600) {
                    Storage::append('medicalPackagesActivity.txt', $content);
                }
                break;

            case '10':
                $contents = Storage::get('auditActivity.txt');
                $size = File::size(storage_path('app/auditActivity.txt'));
                if ($size < 104857600) {
                    Storage::append('auditActivity.txt', $content);
                }
                break;

          default:

            break;
        }

        // foreach($this->logTypes as $type) {
        //     $file = File::get(storage_path('app/' . $type));
        //     $how_much = strlen($file);
        //     $contents = substr($file, $how_much / 2 );
        //     Storage::put($type, $contents);
        // }

        /** CHWILOWO WYŁĄCZONE KASOWANIE DANYCH Z PLIKU */
        // $day = date('d');
        // if ($day == 1) {
        //     $this->clearLogs();
        // }
    }

    private function clearLogs() {
        $checkIfCleared = File::get(storage_path('app/logData.txt'));
        $pos = strpos($checkIfCleared, date('y-m'));
        if (!$pos) {
            foreach($this->logTypes as $type) {
                $file = File::get(storage_path('app/' . $type));
                $how_much = strlen($file);
                $contents = substr($file, $how_much / 2 );
                Storage::put($type, $contents);
            }

            $contents = Storage::get('logData.txt');
            Storage::append('logData.txt', date('Y-m'));
        }


    }

}
