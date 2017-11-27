<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      for($i = 25; $i <= 5000; $i++) {
          DB::table('users')->insert([
              'username' => 'Telemarketer' . $i,
              'first_name' => 'Imie' . $i,
              'last_name' => 'Nazwisko' . $i,
              'email_off' => 'email@email.pl'. $i,
              'password' => bcrypt('secret'),
              'created_at' => '2017-11-24 12:12:12',
              'password_date' => '2017-11-24 12:12:12',
              'last_login' => '2017-11-24 12:12:12',
              'user_type_id' => 1,
              'department_info_id' => 1,
              'dating_type' => 1,
              'start_work' => '2017-11-24',
              'status_work' => 1,
              'phone' => 666666666,
              'description' => 'The telemarketer',
              'student' => 1,
              'salary_to_account' => 1,
              'agency_id' => 1,
              'guid' => base64_encode('secret'),
              'login_phone' => 'user' . $i,
              'rate' => 1,
              'id_manager' => 44,
              'documents' => 1,

          ]);
      }
    }
}
