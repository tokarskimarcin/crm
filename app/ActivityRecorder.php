<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ActivityRecorder extends Model
{
    private $user;
    private $date;
    private $action;

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

      types of activity:
      1 - HR managment
      2 - finances
      3 - admin activity
      4 - janky activity
      5 - work hours actiity
      6 - equipment Activity
      7 - other
    */

    public function __construct($type, $action) {
        $this->user = Auth::user()->id;
        $this->date = date("Y-m-d H:i:s");
        $this->action = $action;

        $string = '';

        if (is_array($this->action)) {
            foreach($this->action as $key => $value) {
                $string .= $key . ' : ' . $value . '. ';
            }
        } else {
            $string = $this->action;
        }

        $content = 'ID: [' . $this->user . '] DATE: [' . $this->date . '] ACTION: [' . $string . ']';

        switch ($type) {
          case '1':
            $contents = Storage::get('hrActivity.txt');
            Storage::append('hrActivity.txt', $content);
            break;

          case '2':
            $contents = Storage::get('financesActivity.txt');
            Storage::append('financesActivity.txt', $content);
            break;

          case '3':
            $contents = Storage::get('adminActivity.txt');
            Storage::append('adminActivity.txt', $content);
            break;

          case '4':
            $contents = Storage::get('jankyActivity.txt');
            Storage::append('jankyActivity.txt', $content);
            break;

          case '5':
            $contents = Storage::get('workHoursActivity.txt');
            Storage::append('workHoursActivity.txt', $content);
            break;

          case '6':
            $contents = Storage::get('equipmentActivity.txt');
            Storage::append('equipmentActivity.txt', $content);
            break;

          case '7':
            $contents = Storage::get('activity.txt');
            Storage::append('activity.txt', $content);
            break;

          default:

            break;
        }


        //var_dump($contents);

    }

}
