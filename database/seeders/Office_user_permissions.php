<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Office_user_permissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
       
        DB::table('office_user_permissions')->insert([
            'name' => 'صلاحية عرض البطاقات',
        ]);
     
        DB::table('office_user_permissions')->insert([
            'name' => 'صلاحية اصدار وثيقة',
        ]);
      
        DB::table('office_user_permissions')->insert([
            'name' => 'صلاحية ادارة التقارير ',
        ]);
   
    }
}
