<?php

use Illuminate\Database\Seeder;

class DKJTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $z = 1;
      for($i = 1; $i <= 10; $i++) {
        $z = ($z == 1) ? 0 : 1 ;
        for($y = 1000; $y <= 4000; $y++){
            DB::table('dkj')->insert([
                'add_date' => '2017-11-27 12:12:12',
                'id_user' => $y,
                'id_dkj' => 44,
                'id_manager' => 44,
                'date_manager' => '2017-11-27 12:12:12',
                'phone' => 666666666,
                'campaign' => 'Nowy jork',
                'comment' => 'Janek lub nie',
                'dkj_status' => $z,
                'manager_status' => $z,
                'edit_dkj' => '2017-11-27 12:12:12',
                'edit_date' => '2017-11-27 12:12:12',
                'department_info_id' => 1,
            ]);

            $z = ($z == 1) ? 0 : 1 ;
        }

      }
    }
}
