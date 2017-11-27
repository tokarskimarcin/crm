<?php

use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 101; $i <= 1000; $i++) {
            DB::table('notifications')->insert([
                'title' => 'Tytuł problemu nr' . $i,
                'content' => 'Konsultant nie słyszy',
                'user_id' => 44,
                'notification_type_id' => 1,
                'department_info_id' => 1,
                'created_at' => '2017-11-24 12:12:12',
                'displayed_by' => null,
                'data_start' => null,
                'data_stop' => null,
                'sec' => null,
                'status' => 1,
            ]);
        }

    }
}
