<?php

use Illuminate\Database\Seeder;

class WorkHoursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 30; $i++) {
            for($y = 100; $y <= 4900; $y++) {
                DB::table('work_hours')->insert([
                    'click_start' => '12:12:12',
                    'click_stop' => '22:12:12',
                    'register_start' => '12:12:12',
                    'register_stop' => '22:12:12',
                    'accept_start' => '12:12:12',
                    'accept_stop' => '22:12:12',
                    'status' => 4,
                    'id_manager' => 44,
                    'id_user' => $y,
                    'accept_sec' => 36000,
                    'date' => '2017-11-' . $i,
                ]);
            }
        }
        for($i = 6; $i <= 50000; $i++) {

        }
    }
}
