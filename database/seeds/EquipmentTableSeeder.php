<?php

use Illuminate\Database\Seeder;

class EquipmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      for($i = 1; $i <= 6000; $i++) {
          DB::table('equipments')->insert([
              'equipment_type_id' => 6,
              'model' => 'Drukarka nr' . $i,
              'serial_code' => 'code nr' . $i . '5000',
              'id_manager' => 44,
              'department_info_id' => 1,

          ]);
      }
    }
}
