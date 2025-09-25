<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class User_Type_TableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->insert([
            'id' =>1,
            'name' => 'Super Admin',
            'slug'=>'مدير',
        ]);
        DB::table('user_types')->insert([
            'name' => 'User',
            'slug'=>' مستخدم',
        ]);
     
       
   
    }
}
